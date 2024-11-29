from flask import Flask, json, request, jsonify
import pandas as pd
from io import StringIO
from data_manipulation import format_types, get_dataframe, insertion_is_valid, verify_if_period_exists_in_dataframe
from generate_graphs import generate_error_graphic, generate_graphics
from store_csv import export_table_to_csv_from_db, store_csv_in_database, get_columns, retrieve_time_column
from create_meta_table import create_meta_table, insert_value
import mysql.connector
from store_json import save_json_aux
import google.generativeai as genai
import os
import numpy as np

app = Flask(__name__)


"""
Rota para processar o CSV & Salvar na DataBase.
@request
-----------------------------
* file_input CSV: arquivo CSV recebido da parte do LARAVEL.  

@return
-----------------------------
* retorna 200 caso tenha salvado corretamente.
* retorna 400 em caso de erro.
"""
@app.route("/save_csv", methods=["POST"])
def save_csv():
    create_meta_table() #
    if 'file_input' not in request.files:
        return jsonify({'error': 'Nenhum arquivo enviado!'}), 400 #Verificação para ver se o arquivo chegou.
    
    # recupera arquivo e nome
    file = request.files['file_input']
    filename = file.filename
    filename = filename.split(".")[0] # trata para tirar a extensão
    
    if file:
        try:
            file_content = file.stream.read().decode('utf-8')
            data = StringIO(file_content)  # Cria um objeto StringIO para que o pandas possa ler
            df = pd.read_csv(data)  # Usando pandas para ler o CSV
            
            store_csv_in_database(df, filename) # função auxiliar para salvar de fato o CSV no banco
            return jsonify({'table_name': filename}), 200
        
        except Exception as e:
            return jsonify({'error': str(e)}), 400
    else:
        return jsonify({'error': 'Arquivo não encontrado no formulário'}), 400


data_frame = None

@app.route("/save_json", methods=["POST"])
def save_json():
    json_data = request.get_json()

    # Acessa os dados e o nome do arquivo (supondo que a estrutura seja {"table": "filename", "data": {...}})
    table = json_data.get('table')
    data = json_data.get('data')
    
    save_json_aux(data,table)

    return jsonify({}), 200


@app.route("/launch_value", methods=["POST"])
def launch_value():
    json_data = request.get_json()

    time = json_data.get('time_column_value')
    time_column_name = json_data.get("time_column")
    column_to_launch = json_data.get('column_to_launch')
    value_to_launch = json_data.get('value_to_launch')

    filename = json_data.get('filename')

    df = get_dataframe(filename)

    path = f'./storage/{filename}.json'
    with open(path, 'r') as file:
        rules_str = file.read()  # Lê o conteúdo como string
        rules = json.loads(rules_str)
        if isinstance(rules, str):  # Se rules for uma string, decodifique novamente
            rules = json.loads(rules)

    is_valid, ids = insertion_is_valid(value_to_launch, df, column_to_launch, rules)

    print(ids)
    if is_valid:
        # Certifique-se de que a coluna de tempo é o índice
        if time_column_name not in df.index.names:
            df.set_index(time_column_name, inplace=True)
        
        # Verifica se o período já existe no índice
        print(time + " " + str(type(time)) + " " + " " + str(type(df.index)))
        print(df.index)
        if float(time) in df.index:
            # Atualiza o valor na linha existente
            # mask = df[time_column_name] == time

            # Atualiza o valor na coluna especificada para as linhas correspondentes
            # df.loc[mask, column_to_launch] = float(value_to_launch)
            # df.at[time, column_to_launch] = float(value_to_launch)
            df.loc[float(time), column_to_launch] = float(value_to_launch)
        else:
            # Adiciona uma nova linha
            new_row = {time_column_name: float(time), column_to_launch: float(value_to_launch)}
            df = pd.concat([df, pd.DataFrame([new_row]).set_index(time_column_name)])

        # Salva o DataFrame no arquivo CSV
        path = f'./csv/{filename}.csv'
        df.to_csv(path)
        return jsonify({}), 200
    else:
        # Gera gráfico de erro
        generate_error_graphic(df, time_column_name, time, column_to_launch, float(value_to_launch), filename)
        return jsonify({"error": [int(id_item) for id_item in ids]}), 500


@app.route("/format_data", methods=["GET"])
def format_data():
    '''
        data => {
            "filename" : string,
            "columnConfigs" : [
                {
                    "nomeColuna" : {
                        "tipo" => type
                        "separador" => string/char
                        "eh_tempo" => bool
                        "american_format" => bool
                    }
                },
            ]
        }
    '''
    configs = request.args.get('values')
    data = json.loads(configs)

    filename = request.args.get('name').strip('"')
    # print("==========================")
    # print(filename)

    df = export_table_to_csv_from_db(filename)
    df, column_name = format_types(df, data)

    df.to_csv(f"./csv/{filename}.csv")

    insert_value(filename, column_name)

    return jsonify({}), 200

"""
Rota para Geração dos gráficos iniciais.
@request
-----------------------------
* file_name STR: nome do arquivo

@return
-----------------------------
* retorna 200 em caso de ter salvado corretamente.
* retorna 400 em caso de erro.
"""
@app.route("/generate_graphics", methods=["GET"])
def generate_graphics_route():
    file_name = request.args.get('file_name').strip('"')
    db_connection = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="hackathon"
    )

    # Criando um cursor para executar a consulta
    cursor = db_connection.cursor()

    # Consulta SQL
    query = "SELECT * FROM csv_files WHERE file_name = %s"

    # Executando a consulta
    cursor.execute(query, (file_name,))

    # Obtendo os resultados
    time_column = cursor.fetchall()[0][1]
    # print(time_column) => sabemos quem é a time column (!!!)
    
    data_frame = pd.read_csv(f"./csv/{file_name}.csv", index_col=0)
    print('====================================')
    generate_graphics(data_frame, time_column, filename=file_name)
    
    path = f"{file_name}.png" #path do laravel...
    
    return jsonify({'path': path}), 200



"""
Rota para conseguir acessar as colunas e tipos de dados disponíveis.
@request
-----------------------------
* table STRING: nome do arquivo CSV (nome da tabela) a ser recuperado os dados.  

@return
-----------------------------
* retorna 200 caso tenha salvado corretamente.
* retorna 400 em caso de erro.
"""
@app.route("/get_metadata", methods=["GET"])
def get_metadata():    
    try:
        name = request.args.get('table') # tenta acessar o atributo name vindo do LARAVEL
        metadata = get_columns(name) # função auxiliar para recuperar as colunas

        return jsonify({'dados': metadata}), 200
    
    except Exception as e:
        return jsonify({'error': str(e)}), 400

"""
Rota para aplicar o filtro usando engenharia de Prompt e Gemini.
@request
-----------------------------
* filename STRING: nome do arquivo CSV (nome da tabela) a ser recuperado os dados.  
* filter_string STRING: string que o usuário enviou para ser aplicado o processo de filtragem.  

@return
-----------------------------
* retorna 200 caso tenha salvado corretamente.
* retorna 400 em caso de erro.
"""
@app.route("/apply_filter", methods=["GET"])
def apply_filter():
    filename = request.args.get('filename')   
    filter_string = request.args.get('filter_string')   
    
    df = pd.read_csv(f"./csv/{filename}.csv", index_col=0) # recupera o dataframe
    cols = list(json.loads( get_columns(filename) ).keys())
    time_column = retrieve_time_column(filename)
    

    genai.configure(api_key='AIzaSyBMZxHGqpizSJ8zE5xtG-NUXgGyejecpTg')

    # Create the model
    generation_config = {
        "temperature": 1,
        "top_p": 0.95,
        "top_k": 40,
        "max_output_tokens": 8192,
        "response_mime_type": "application/json",
    }

    model = genai.GenerativeModel(
        model_name="gemini-1.5-pro",
        generation_config=generation_config,
    )

    chat_session = model.start_chat(
        history=[
            {
            "role": "user",
            "parts": [
                "\"\"\"\n        A aplicação possui um dataframe em memória chamado df. Este dataframe contém várias colunas, cujos nomes exatos serão passados dinamicamente. O dataframe representa uma série temporal, onde o nome da coluna que contém o tempo será passado também. O usuário fornecerá um texto (prompt) especificando um filtro para ser aplicado a esse dataframe.\n\n        O seu objetivo é:\n\n        1) Identificar e extrair os nomes das colunas mencionadas pelo usuário no prompt, verificando se elas correspondem corretamente aos nomes das colunas no dataframe e fazendo uma conversão/adaptação caso necessário.\n        \n        2) Após o passo anterior, gere um código Python válido e eficiente que aplique o filtro no dataframe. Esse código deve ser gerado como uma única instrução utilizando o método `query()` do Pandas para realizar a filtragem. Use operadores lógicos como `&` (para \"e\") e `|` (para \"ou\") no código de filtragem. A variável que armazenará os resultados do filtro será chamada `filtro`.\n\n        A resposta deve ser um JSON com a estrutura:\n        \n        {{\"status\": \"1\", \"codigo\": \"instrucao\" }}\n        \n        \n        Onde \"instrucao\" é o código Python gerado para aplicar o filtro no dataframe, utilizando os nomes de coluna fornecidos e garantindo que o filtro seja corretamente aplicado.\n\n        Exemplos de prompts fornecidos pelo usuário podem ser:\n\n        1) \"Resgatar um valor da coluna <NOME_COLUNA> na coluna do tempo <COLUNA_TEMPO>.\"\n        2) \"Selecionar a relação de medidas do último ano da coluna <NOME_COLUNA>.\"\n\n        O código gerado deve ser o mais eficiente possível e garantir que o filtro seja aplicado corretamente, usando o método `query()` do pandas.\n\n        Gere a resposta no formato JSON com a respectiva instrução gerada para aplicar o filtro. Evite usar acentos, crases ou caracteres especiais no código gerado. A instrução gerada deve ser única e em uma única linha de código Python.\n        Não use and e or dentro da função query, isso pode acarretar em erro de sintaxe.\n        \"\"\"\n    ",
            ],
            },
            {
            "role": "model",
            "parts": [
                "```json\n{\"status\": \"1\", \"codigo\": \"filtro = df.query(\\\"<NOME_COLUNA> == <VALOR_DA_COLUNA> & <COLUNA_TEMPO> == <VALOR_DA_COLUNA_TEMPO>\\\")\"}\n```",
            ],
            },
            {
            "role": "user",
            "parts": [
                "filtro = df.query('Semana == 20')\nfiltro = df.query('Holandesa > 30')\nfiltro = df.query('Gir < 10')\nfiltro = df.query('Semana > 10 & Holandesa < 25')\nfiltro = df.query('Gir.isna()')\nfiltro = df.query('Semana in [20, 25, 30]')\nfiltro = df.query('coluna_texto.str.contains(\"palavra\")')\nfiltro = df.query('Holandesa + Jersey > 50')\nfiltro = df.query('Semana > 20 & Gir < 15')\n\n",
            ],
            },
            {
            "role": "model",
            "parts": [
                "```json\n{\"status\": \"1\", \"codigo\": \"filtro = df.query(\\\"Semana == 20 & Holandesa > 30 & Gir < 10 & Semana > 10 & Holandesa < 25 & Gir.isna() & Semana in [20, 25, 30] & coluna_texto.str.contains('palavra') & Holandesa + Jersey > 50 & Semana > 20 & Gir < 15\\\")\"}\n```",
            ],
            },
            {
            "role": "user",
            "parts": [
                "Prompt informado pelo usuário: dado que as colunas são ['Semana', Holandesa', 'Gir'], e a coluna referente ao tempo é Semana. Preciso que você recupere o valor presenta na coluna Holandesa, cuja semana é igual a 20.\n",
            ],
            },
            {
            "role": "model",
            "parts": [
                "```json\n{\"status\": \"1\", \"codigo\": \"filtro = df.query(\\\"Semana == 20\\\")[\\\"Holandesa\\\"]\"}\n\n```",
            ],
            },
            {
            "role": "user",
            "parts": [
                "Preciso dos valores das colunas Gir e Holandesa, na semana 32.\n",
            ],
            },
            {
            "role": "model",
            "parts": [
                "```json\n{\"status\": \"1\", \"codigo\": \"filtro = df.query(\\\"Semana == 32\\\")[[\\\"Gir\\\", \\\"Holandesa\\\"]\"}\n\n```",
            ],
            },
            {
            "role": "user",
            "parts": [
                "filtro = df.query(\"Semana == 32\")[[\"Gir\", \"Holandesa\"]]\n",
            ],
            },
            {
            "role": "model",
            "parts": [
                "```json\n{\"status\": \"1\", \"codigo\": \"filtro = df.query(\\\"Semana == 32\\\")[[\\\"Gir\\\", \\\"Holandesa\\\"]\"}\n```",
            ],
            },
            {
            "role": "user",
            "parts": [
                "Precisa fechar os dois colchetes\n",
            ],
            },
            {
            "role": "model",
            "parts": [
                "```json\n{\"status\": \"1\", \"codigo\": \"filtro = df.query(\\\"Semana == 32\\\")[[\\\"Gir\\\", \\\"Holandesa\\\"]]\"}\n```",
            ],
            },
            {
            "role": "user",
            "parts": [
                "Preciso do resultado de todas as colunas onde a semana é maior que 16.\n",
            ],
            },
            {
            "role": "model",
            "parts": [
                "```json\n{\"status\": \"1\", \"codigo\": \"filtro = df.query(\\\"Semana > 16\\\")\"}\n```",
            ],
            },
        ]
        )

    response = chat_session.send_message(f"""Prompt informado pelo usuário: dado que as colunas são {cols}, e a coluna referente ao tempo é {time_column} => {filter_string}""")

    result = response.text
    print(result)
    
    query = result
    query = json.loads(query)
    codigo = query["codigo"]
    
    print(codigo)
    
    my_context = {'df': df, 'filtro': None}
    exec(codigo, None, my_context)
    
    # print(my_context['filtro'])
    result = my_context['filtro']
    
    if(type(result) == np.float64):
        return jsonify({'data': result}), 200
    
    data_dict = result.to_json(orient='records')
    print(result)
    print(type(result))
    # print(data_dict)

    return jsonify({'data': data_dict}), 200

# @app.route("/save_register", methods=["POST"])
# def save_register():

#     # TODO: realizar o tratamento/rotina de inserção aqui

#     #save_register(request.get_json())

#     data_frame = get_columns("month_value_1")

#     print(data_frame)

#     return jsonify({}), 200

if __name__ == "__main__":
    app.run(debug=True)