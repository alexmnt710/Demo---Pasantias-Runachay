from flask import Flask, request, jsonify
import os
import json
from werkzeug.utils import secure_filename
from docx import Document

app = Flask(__name__)

UPLOAD_FOLDER = "/app/uploads"
PROCESSED_FOLDER = "/app/processed"
os.makedirs(UPLOAD_FOLDER, exist_ok=True)
os.makedirs(PROCESSED_FOLDER, exist_ok=True)

@app.route('/process', methods=['POST'])
def process_document():
    try:
        if 'file' not in request.files or 'user_data' not in request.form:
            return jsonify({"error": "Faltan datos"}), 400

        file = request.files['file']
        user_data = json.loads(request.form['user_data'])

        # Guardar archivo subido
        filename = secure_filename(file.filename)
        file_path = os.path.join(UPLOAD_FOLDER, filename)
        file.save(file_path)

        # Procesar archivo
        processed_file_path = replace_variables(file_path, user_data)

        return jsonify({
            "message": "Documento procesado correctamente",
            "download_link": f"/download/{os.path.basename(processed_file_path)}"
        })

    except Exception as e:
        return jsonify({"error": f"Error al procesar documento: {str(e)}"}), 500


def replace_variables(file_path, user_data):
    document = Document(file_path)

    replacements = {
        "user.period": user_data.get("period", ""),
        "userperiod": user_data.get("period", ""),
        "city": user_data["contract"].get("city", ""),
        "day": user_data["contract"].get("day_today", ""),
        "datetoday": user_data["contract"].get("date_today", ""),
        "INSTITUCIONCOOWNERNAME": user_data["institution"].get("co_owner_name", ""),
        "CITY": user_data["contract"].get("city", ""),
    }

    for para in document.paragraphs:
        for key, value in replacements.items():
            if key in para.text:
                para.text = para.text.replace(f"${{{key}}}", value)

    processed_file_path = os.path.join(PROCESSED_FOLDER, "processed_" + os.path.basename(file_path))
    document.save(processed_file_path)

    return processed_file_path

@app.route('/download/<filename>', methods=['GET'])
def download_file(filename):
    return f"Archivo disponible en: {PROCESSED_FOLDER}/{filename}"

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
