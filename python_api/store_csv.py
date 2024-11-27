from flask import json
import pandas as pd
import mysql.connector

type_mapping = {
    'int64': 'INT',
    'float64': 'FLOAT',
    'object': 'VARCHAR(255)',
    'bool': 'BOOLEAN',
    'datetime64[ns]': 'DATETIME'
}

# aux function to map the python datatypes to mysql datatypes
def map_dtype(dtype):
    return type_mapping.get(str(dtype), 'VARCHAR(255)')

def store_csv_in_database(df: pd.DataFrame, filename: str):
    
    df = df.fillna(-1) # because not a number values
    
    db_connection = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="hackathon"
    )

    cursor = db_connection.cursor() #realiza a conexão
    
    columns_mysql = ""
    cont = 1
    for column, dtype in zip(df.columns, df.dtypes):
        sql_type = map_dtype(dtype)
        if cont < len(df.columns):
            columns_mysql += f"{column} {sql_type},"
        else:
            columns_mysql += f"{column} {sql_type}"
        cont += 1

    # Criar a tabela no MySQL baseado nos dados do CSV...
    create_table_txt = f"CREATE TABLE IF NOT EXISTS {filename} (" + columns_mysql + ");"
    # print(create_table_txt)
    cursor.execute(create_table_txt)
    
    colunas_string = f"({', '.join(df.columns)})"
    placeholders = ", ".join(["%s"] * len(df.columns))
    try:
        for _, row in df.iterrows():
            cursor.execute(f"""
            INSERT INTO {filename} {colunas_string}
            VALUES ({placeholders})
            """, tuple(row))
        db_connection.commit()
        print(f"{len(df)} registros inseridos com sucesso na tabela.")
    except mysql.connector.Error as err:
        print(f"Erro ao inserir dados: {err}")
    finally:
        cursor.close()
        db_connection.close()


def save_register(register):
    pass


def get_columns(table_name: str):
    try:
        db_connection = mysql.connector.connect(
            host="127.0.0.1",
            user="root",
            password="",
            database="hackathon"
        )
        
        cursor = db_connection.cursor()
        query = f"DESCRIBE {table_name}"
        cursor.execute(query)
        results = cursor.fetchall()
        
        # Criar o dicionário com nome da coluna como chave e tipo como valor
        schema_dict = {row[0]: row[1] for row in results}
        
        # Converter para JSON
        schema_json = json.dumps(schema_dict, indent=4)
        return schema_json

    except mysql.connector.Error as err:
        print(f"Erro ao obter o esquema da tabela: {err}")

    finally:
        db_connection.close()


def export_table_to_csv_from_db(table_name: str):
    try:
        db_connection = mysql.connector.connect(
            host="127.0.0.1",
            user="root",
            password="",
            database="hackathon"
        )
        
        query = f"SELECT * FROM {table_name}"
        df = pd.read_sql(query, db_connection)
        return df

    except mysql.connector.Error as err:
        print(f"Erro ao exportar a tabela: {err}")

    finally:
        db_connection.close()
