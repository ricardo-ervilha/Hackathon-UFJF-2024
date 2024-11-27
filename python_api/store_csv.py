from flask import json
import pandas as pd
import mysql.connector

# MAPEAMENTOS UTILIZADOS ENTRE PANDAS E MYSQL
type_mapping = {
    'int64': 'INT',
    'float64': 'FLOAT',
    'object': 'VARCHAR(255)',
    'bool': 'BOOLEAN',
    'datetime64[ns]': 'DATETIME'
}

# Função para realizar o mapeamento baseado no dicionário acima
def map_dtype(dtype):
    return type_mapping.get(str(dtype), 'VARCHAR(255)')

"""
Função auxiliar para realizar o armazenamento de um CSV dentro da Database.
@parameter
-----------------------------
* df DataFrame: csv recebido no formato pd.DataFrame.
* filename str: string informando nome salvado da tabela no MYSQL.  

@return
-----------------------------
* retorna True caso tenha salvado corretamente.
"""
def store_csv_in_database(df: pd.DataFrame, filename: str):
    
    df = df.fillna(-1) # PREENCHE VALORES NAN COMO -1
    
    db_connection = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="hackathon"
    ) #conexão do banco de dados

    cursor = db_connection.cursor() #realiza a conexão
    
    # criando a query das colunas e tipos de dados baseado no mapeamento...
    columns_mysql = ""
    cont = 1
    for column, dtype in zip(df.columns, df.dtypes):
        sql_type = map_dtype(dtype)
        if cont < len(df.columns):
            columns_mysql += f"{column} {sql_type},"
        else:
            columns_mysql += f"{column} {sql_type}"
        cont += 1

    #------------------------------------------------
    #query de create table
    create_table_txt = f"CREATE TABLE IF NOT EXISTS {filename} (" + columns_mysql + ");" 
    cursor.execute(create_table_txt)
    #------------------------------------------------
    
    #------------------------------------------------
    #inserção dos valores dentro do banco...
    colunas_string = f"({', '.join(df.columns)})" #obtém colunas
    placeholders = ", ".join(["%s"] * len(df.columns)) #obtém placeholders "%s"
    try:
        for _, row in df.iterrows():
            cursor.execute(f"""
            INSERT INTO {filename} {colunas_string}
            VALUES ({placeholders})
            """, tuple(row))
        db_connection.commit()
    except mysql.connector.Error as err:
        return False
    finally:
        cursor.close()
        db_connection.close()
        return True


# def save_register(register):
#     pass

"""
Função auxiliar para obter as colunas e tipos de dados das colunas em formato JSON.
@parameter
-----------------------------
* table_name str: string informando nome salvado da tabela no MYSQL.  

@return
-----------------------------
* retorna True caso tenha salvado corretamente.
"""
def get_columns(table_name: str):
    try:
        db_connection = mysql.connector.connect(
            host="127.0.0.1",
            user="root",
            password="",
            database="hackathon"
        ) #conexão no banco...
        
        #------------------------------------
        #recuperação das colunas via DESCRIBE
        cursor = db_connection.cursor()
        query = f"DESCRIBE {table_name}"
        cursor.execute(query)
        results = cursor.fetchall()
        #------------------------------------
        
        # Criar o dicionário com nome da coluna como chave e tipo como valor
        schema_dict = {row[0]: row[1] for row in results}
        
        # Converter para JSON
        schema_json = json.dumps(schema_dict, indent=4)
        db_connection.close()
        return schema_json

    except mysql.connector.Error as err:
        print(f"Erro ao obter o esquema da tabela: {err}")

        

"""
Função auxiliar para obter o CSV a partir do banco de dados MYSQL.
@parameter
-----------------------------
* table_name str: string informando nome salvado da tabela no MYSQL.  

@return
-----------------------------
* retorna objeto DataFrame representando o csv desejado.
"""
def export_table_to_csv_from_db(table_name: str):
    try:
        db_connection = mysql.connector.connect(
            host="127.0.0.1",
            user="root",
            password="",
            database="hackathon"
        )
        table_name = table_name.strip('"')
        query = f"SELECT * FROM `{table_name}`"
        df = pd.read_sql(query, db_connection)
        db_connection.close()
        return df

    except mysql.connector.Error as err:
        print(f"Erro ao exportar a tabela: {err}")
        
