# AIzaSyCbT1zqyY7AI53LMv5Tv3wJdwpc3f-kmZs

genai.configure(api_key='AIzaSyCbT1zqyY7AI53LMv5Tv3wJdwpc3f-kmZs')

model = genai.GenerativeModel('gemini-pro')

response = model.generate_content("Seguinte meu amigo, tu vai me retornar um código python. O código é só um print para hello world!. Me retorne somente a instrução do print. Retorne sem crase o código!")
exec(response.text)