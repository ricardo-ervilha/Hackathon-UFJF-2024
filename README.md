# Hackathon-UFJF-2024

Repositório contendo os artefatos produzidos para o Hackathon Rerum na Semana da Computação-UFJF 2024.

## Estudante 1: Ricardo Ervilha Silva
## Estudante 2: Yan Messias de Azevedo Fonseca

# Instruções de Instalação

Para a parte do PHP, recomendamos que baixe o XAMPP, disponível no link: https://www.apachefriends.org/pt_br/download.html. Após baixá-lo, certifique de dar "start" tanto no servidor APACHE quanto no MYSQL.
Instale também o composer: https://getcomposer.org/.

```bash
git clone https://github.com/ricardo-ervilha/Hackathon-UFJF-2024.git
cd .\hackathon\
composer install #dependências do composer
cp .env.example .env #criar arquivo de configuração .env. 
php artisan key:generate # gerar chave do artisan
php artisan serve # abre o servidor do php
```

Após executar a parte do PHP, clique no ícone "Admin" do MySQL no Xamppp. Esse ícone o levará para o PHPMyAdmin, uma interface do PHP para usar o MySQL. Na interface, crie um novo banco chamado "hackathon".
Em seguida, instale o Node.JS: https://nodejs.org/en/download/package-manager. Após instalá-lo, rode os seguintes comandos:

```bash
npm install # abra um novo terminal, e dê cd .\hackathon\
npm run build 
npm run dev # abre o servidor npm
```

Por fim, falta somente a parte específica do Python. Recomendamos antes que crie um ambiente virtual separado através do gerenciador conda, ou do próprio python. Após criar o ambiente, rode o seguinte: 

```bash
pip install -r requirements.txt # instalar dependências na parte do python (faça em outro terminal separado)
cd .\python_api\
python main.py
```

Após isso, estará tudo configurado para a execução do projeto na rota: http://127.0.0.1:8000.