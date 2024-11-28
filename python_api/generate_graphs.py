import matplotlib.pyplot as plt
from store_csv import export_table_to_csv_from_db
import pandas as pd

# def generate_time_series_graph(dataset_name: str):
#     df = export_table_to_csv_from_db(dataset_name) #recover dataset with datas

def generate_graphics(x, y, filename, title="", xlabel='Date', ylabel='example', dpi=100):
    plt.figure(figsize=(15,4), dpi=dpi)
    plt.plot(x, y, color='tab:red')
    plt.gca().set(title=title, xlabel=xlabel, ylabel=ylabel)
    plt.savefig(f"../hackathon/public/img/{filename}.png")