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


def get_dataframe(filename: str):
    path = f'./csv/{filename}.csv'
    return pd.read_csv(path, index_col=0)


def verify_if_period_exists_in_dataframe(df, period, time_column):
    return period in df[time_column].values

"""
Verifica se a inserção é válida com base nas regras fornecidas.
"""
def insertion_is_valid(value, df, column_name, rules):
    column_rules = rules[column_name]["rules"]

    is_valid = True
    wrong_ids = []

    for rule in column_rules:
        rule_id = rule["id"]

        if rule_id == 0 and is_superior_to_the_curve_of_a_particular_column(value, df, column_name, int(rule["value"])):
            is_valid = False
            wrong_ids.append(rule_id)
        elif rule_id == 1 and is_bigger_than_average_of_the_previous_five_releases(value, df, column_name, int(rule["value"])):
            is_valid = False
            wrong_ids.append(rule_id)
        elif rule_id == 2 and is_bigger_than_previous_release(value, df, column_name, int(rule["value"])):
            is_valid = False
            wrong_ids.append(rule_id)
        elif rule_id == 3 and is_bigger_than_highest_value_ever_recorded(value, df, column_name, int(rule["value"])):
            is_valid = False
            wrong_ids.append(rule_id)
        elif rule_id == 4 and is_less_than_lowest_value_ever_recorded(value, df, column_name, int(rule["value"])):
            is_valid = False
            wrong_ids.append(rule_id)

    return is_valid, wrong_ids


def is_superior_to_the_curve_of_a_particular_column(value, df, column_name, rule):
    average = df[column_name].mean()
    return float(value) > average * (1 + rule/100)


def is_bigger_than_average_of_the_previous_five_releases(value, df, column_name, rule):
    last_five = df[column_name].iloc[-5:]  # Obtém os últimos cinco valores da coluna
    average = last_five.mean() 
    return float(value) > average * (1 + rule/100)


def is_bigger_than_previous_release(value, df, column_name, rule):
    if df.empty:
        return True  # Se não houver lançamentos anteriores, permite a inserção
    previous_value = df[column_name].iloc[-1]  # Obtém o último valor

    if previous_value == 0:
        previous_value = 1

    return float(value) > previous_value * (1 + rule/100)


def is_bigger_than_highest_value_ever_recorded(value, df, column_name, rule):
    if df.empty:
        return True  # Se não houver registros, permite a inserção
    highest_value = df[column_name].max()  # Obtém o maior valor registrado
    return float(value) > highest_value * (1 + rule/100)


def is_less_than_lowest_value_ever_recorded(value, df, column_name, rule):
    if df.empty:
        return True  # Se não houver registros, não permite a inserção
    min_value = df[column_name].min()  # Obtém o menor valor registrado
    return float(value) < min_value * (1 - rule/100)