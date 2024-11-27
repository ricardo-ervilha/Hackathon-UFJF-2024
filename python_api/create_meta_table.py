import mysql.connector

"""
Tabela responsável por organizar os metadados...
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
        time_column TEXT NOT NULL,
        imported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );"""
    cursor.execute(create_table_txt)
    
    cursor.close()
    db_connection.close()