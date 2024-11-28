import pandas as pd

def format_types(df: pd.DataFrame, configs: dict):
    for col_name, col_info_list in configs.items():
        # O JSON contém uma lista de configurações para cada coluna
        col_info = col_info_list[0]

        tipo = col_info["tipo"]
        separador = col_info["separador"]
        eh_tempo = col_info["eh_tempo"]
        is_american_format = col_info["american_format"]

        # Converter para datetime se for tipo de tempo
        if tipo == "datetime":
            if is_american_format:
                date_format = f"%m{separador}%d{separador}%Y"
            else:
                date_format = f"%d{separador}%m{separador}%Y"
            df[col_name] = pd.to_datetime(df[col_name], format=date_format, errors="coerce")
        elif tipo == "float":
            df[col_name] = pd.to_numeric(df[col_name], errors="coerce", downcast="float")
        elif tipo == "int":
            df[col_name] = pd.to_numeric(df[col_name], errors="coerce", downcast="integer")
        elif tipo == "string":
            df[col_name] = df[col_name].astype(str)

        # Marcar a coluna como índice se for tempo
        if eh_tempo:
            time_column = col_name

    return df, time_column


def insertion_is_valid(value, df, column_name, rules):
    """
    Verifica se a inserção é válida com base nas regras fornecidas.
    """
    column_rules = rules[column_name]["rules"]

    for rule in column_rules:
        rule_id = rule["id"]

        if rule_id == 0:
            continue
        elif rule_id == 1 and not is_bigger_than_average_of_the_previous_five_releases(value, df, column_name):
            return False
        elif rule_id == 2 and not is_bigger_than_previous_release(value, df, column_name):
            return False
        elif rule_id == 3 and not is_bigger_than_highest_value_ever_recorded(value, df, column_name):
            return False
        elif rule_id == 4 and not is_less_than_highest_value_ever_recorded(value, df, column_name):
            return False

    return True

def is_bigger_than_average_of_the_previous_five_releases(value, df, column_name):
    last_five = df[column_name].iloc[-5:]  # Obtém os últimos cinco valores da coluna
    if len(last_five) < 5:
        return False  
    average = last_five.mean() 
    return value > average


def is_bigger_than_previous_release(value, df, column_name):
    if df.empty:
        return True  # Se não houver lançamentos anteriores, permite a inserção
    previous_value = df[column_name].iloc[-1]  # Obtém o último valor
    return value > previous_value


def is_bigger_than_highest_value_ever_recorded(value, df, column_name):
    if df.empty:
        return True  # Se não houver registros, permite a inserção
    highest_value = df[column_name].max()  # Obtém o maior valor registrado
    return value > highest_value


def is_less_than_highest_value_ever_recorded(value, df, column_name):
    if df.empty:
        return False  # Se não houver registros, não permite a inserção
    highest_value = df[column_name].max()  # Obtém o maior valor registrado
    return value < highest_value