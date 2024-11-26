from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route("/", methods=["GET"])
def home():
    return "Home"

@app.route("/name", methods=["POST"])
def name():
    data = request.get_json()

    print(data)

    return jsonify({}), 200

if __name__ == "__main__":
    app.run(debug=True)