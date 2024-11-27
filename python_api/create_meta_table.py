import mysql.connector

"""
Função auxiliar para criar uma meta_tabela csv_files, que armazenará "NOME_CSV" <=> "COLUNA_TEMPO"
@parameter
-----------------------------

@return
-----------------------------
"""
def create_meta_table():
    db_connection = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="hackathon"
    )
    
    cursor = db_connection.cursor()
    
    create_table_txt = f"""CREATE TABLE IF NOT EXISTS csv_files (
        file_name TEXT NOT NULL,
        time_column TEXT NOT NULL
    );"""
    cursor.execute(create_table_txt)
    
    cursor.close()
    db_connection.close()

def insert_value(filename: str, time_column: str):
    db_connection = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="hackathon"
    )
    
    filename = filename.strip('"')
    
    cursor = db_connection.cursor()

    # Corrigido: Coloquei as variáveis entre aspas na query SQL
    insert_column_value = f"INSERT INTO csv_files (file_name, time_column) VALUES ('{filename}', '{time_column}')"

    # Executando a consulta
    cursor.execute(insert_column_value)
    
    # Confirmando a transação
    db_connection.commit()
    
    # Fechando a conexão
    cursor.close()
    db_connection.close()