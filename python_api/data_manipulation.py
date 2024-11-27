import pandas as pd

def format_types(df: pd.DataFrame, configs: object):
    for col_config in configs:
        for col_name, col_info in col_config.items():
            tipo = col_info["tipo"]
            separador = col_info["separador"]
            eh_tempo = col_info["eh_tempo"]
            is_american_format = col_info["is_american_format"]

            # Converter para datetime se for tipo de tempo
            if tipo == "datetime":
                separador = "."

                if is_american_format:
                    date_format = "%m" + separador + "%d" + separador + "%Y"
                else:
                    date_format = "%d" + separador + "%m" + separador + "%Y"

                df[col_name] = pd.to_datetime(df[col_name], format=date_format, errors="coerce") # separador = "%Y-%m-%d"
            elif tipo == "float":
                df[col_name] = pd.to_numeric(df[col_name], errors="coerce", downcast="float")
            elif tipo == "int":
                df[col_name] = pd.to_numeric(df[col_name], errors="coerce", downcast="integer")
            elif tipo == "string":
                df[col_name] = df[col_name].astype(str)
            
            # Marcar a coluna como índice se for tempo
            if eh_tempo:
                df.set_index(col_name, inplace=True)
    
    return df