from flask import Flask, json, request, jsonify
import pandas as pd
from io import StringIO
from data_manipulation import format_types
from generate_graphs import generate_graphics
from store_csv import export_table_to_csv_from_db, store_csv_in_database, get_columns
from create_meta_table import create_meta_table, insert_value
import mysql.connector

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

    filename = request.args.get('name')

    df = export_table_to_csv_from_db(filename)
    df, column_name = format_types(df, data)

    df.to_csv("temp_storage.csv")

    insert_value(filename, column_name)

    return jsonify({}), 200


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
    print(time_column)
    data_frame = pd.read_csv("yan.csv", header=0)
    generate_graphics(data_frame[time_column], data_frame['Revenue'], title="Testando", filename=file_name)
    
    path = f"{file_name}.png"
    
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

    

# @app.route("/save_register", methods=["POST"])
# def save_register():

#     # TODO: realizar o tratamento/rotina de inserção aqui

#     #save_register(request.get_json())

#     data_frame = get_columns("month_value_1")

#     print(data_frame)

#     return jsonify({}), 200

if __name__ == "__main__":
    #create_meta_table() # create meta table if not exists...
    app.run(debug=True)