import matplotlib.pyplot as plt
from store_csv import export_table_to_csv_from_db
import pandas as pd

# def generate_time_series_graph(dataset_name: str):
#     df = export_table_to_csv_from_db(dataset_name) #recover dataset with datas

def generate_graphics(data_frame, time_column, filename="output.png"):
    """
    Gera um gráfico com várias curvas baseadas nas colunas do DataFrame,
    plotando cada coluna em função da coluna do tempo.

    Parameters:
    - data_frame: DataFrame contendo os dados.
    - time_column: Nome da coluna que representa o tempo.
    - title: Título do gráfico.
    - filename: Nome do arquivo para salvar o gráfico.
    """
    # Verificar se a coluna de tempo existe
    if time_column not in data_frame.columns:
        raise ValueError(f"A coluna '{time_column}' não está presente no DataFrame.")
    
    normalized_data = data_frame.copy()
    for column in data_frame.columns:
        if column != time_column:
            col_min = data_frame[column].min()
            col_max = data_frame[column].max()
            normalized_data[column] = (data_frame[column] - col_min) / (col_max - col_min)

    # Configurar o gráfico
    plt.figure(figsize=(20, 10))
    plt.xlabel(time_column)
    plt.xticks(rotation=90)
    plt.ylabel("Variação dos Valores (NORMALIZADO)")
    
    # Iterar pelas colunas, excluindo a de tempo
    for column in data_frame.columns:
        if column != time_column:
            plt.plot(normalized_data[time_column], normalized_data[column], label=column)

    # Configurar a legenda
    plt.legend(loc='best')
    plt.grid(True)
    
    # Salvar e exibir o gráfico
    plt.savefig(f"../hackathon/public/img/{filename}.png")
    # plt.show()