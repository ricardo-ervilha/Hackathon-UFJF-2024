import matplotlib.pyplot as plt
from store_csv import export_table_to_csv_from_db
import pandas as pd
import seaborn as sns
import numpy as np

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
    
    # normalized_data = data_frame.copy()
    # for column in data_frame.columns:
    #     if column != time_column:
    #         col_min = data_frame[column].min()
    #         col_max = data_frame[column].max()
    #         normalized_data[column] = (data_frame[column] - col_min) / (col_max - col_min)

    # Configurar o gráfico
    plt.figure(figsize=(20, 10))
    plt.xlabel(time_column)
    plt.xticks(rotation=90)
    plt.ylabel("Variação dos Valores")
    
    # Iterar pelas colunas, excluindo a de tempo
    for column in data_frame.columns:
        if column != time_column:
            # print(column)
            plt.plot(data_frame[time_column], data_frame[column], label=column)

    # Configurar a legenda
    plt.legend(loc='best')
    plt.grid(True)
    
    # Salvar e exibir o gráfico
    plt.savefig(f"../hackathon/public/img/{filename}.png")
    # plt.show()


def generate_error_graphic(data_frame, time_column, time_value, column_target, target_column_error, filename):
    # Verificar se time_column é do tipo datetime e, se não for, convertê-lo
    if not np.issubdtype(data_frame[time_column].dtype, np.datetime64):
        try:
            data_frame[time_column] = pd.to_datetime(data_frame[time_column])
        except Exception:
            raise ValueError(f"Não foi possível converter '{time_column}' para datetime.")
    
    # # Garantir que time_value também esteja no mesmo formato
    # if not isinstance(time_value, pd.Timestamp):
    #     time_value = pd.to_datetime(time_value)

    # Configurar o estilo do Seaborn
    sns.set_theme(style="whitegrid")

    # Criar a figura e os eixos
    plt.figure(figsize=(20, 10))
    ax = sns.lineplot(
        x=data_frame[time_column],
        y=data_frame[column_target],
        marker="o",
        label="Curva principal",
        color="blue"
    )

    # Adicionar o ponto de erro
    ax.scatter(
        [time_value],  # Lista com um único valor no eixo X
        [target_column_error],  # Lista com um único valor no eixo Y
        color="red",
        s=100,  # Tamanho do ponto
        label="Erro"
    )

    # Adicionar texto aos pontos do gráfico principal
    for i, (x, y) in enumerate(zip(data_frame[time_column], data_frame[column_target])):
        ax.text(
            x, y, f'{y:.2f}', fontsize=10, ha='right', color='blue'
        )

    # Adicionar texto ao ponto de erro
    ax.text(
        time_value,
        target_column_error,
        f'{target_column_error:.2f}',
        fontsize=12,
        ha='center',
        color='red'
    )

    # Configurar os rótulos do eixo X com rotação
    ax.set_xticks(data_frame[time_column])
    ax.set_xticklabels(
        data_frame[time_column].dt.strftime("%Y-%m-%d"), 
        rotation=45, 
        fontsize=10
    )

    # Configurar os rótulos dos eixos
    ax.set_xlabel(time_column, fontsize=14)
    ax.set_ylabel("Variação dos valores durante o tempo", fontsize=14)

    # Adicionar título, legenda e salvar o gráfico
    ax.legend(loc='best')
    plt.tight_layout()  # Ajustar layout para evitar sobreposição
    plt.savefig(f"../hackathon/public/img/{filename}_error.png")

