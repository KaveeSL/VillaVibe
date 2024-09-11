from flask import Flask, request, jsonify, render_template
import joblib
import spacy
from flask_cors import CORS, cross_origin
from transformers import pipeline

# Initialize Flask app
app = Flask(__name__, template_folder='.')

# Enable CORS for cross-origin requests
CORS(app, resources={r"/*": {"origins": ["http://127.0.0.1", "http://localhost"]}})

# Load pre-trained sentiment analysis model and TF-IDF vectorizer
model = joblib.load('best_logistic_regression_model.pkl')
tfidf_vectorizer = joblib.load('tfidf_vectorizer.pkl')

# Load spaCy model for tokenization
nlp = spacy.load('en_core_web_sm')

# Load hate speech detection model using Hugging Face's transformers pipeline
hate_speech_detector = pipeline("text-classification", model="unitary/toxic-bert")

# Tokenizer function using spaCy
def spacy_tokenizer(text):
    print(f"Tokenizing text: {text}")
    doc = nlp(text)
    tokens = [token.lemma_ for token in doc if not token.is_stop and not token.is_punct]
    tokenized = " ".join(tokens)
    print(f"Tokenized text: {tokenized}")
    return tokenized

# Route for the homepage
@app.route('/')
def index():
    print("Rendering index.html")
    return render_template('index.html')

@app.route('/submit_review', methods=['POST'])
@cross_origin(origin='localhost', headers=['Content-Type', 'Authorization'])
def submit_review():
    review = request.form.get('reviewerMessage', '')

    print(f"Received review: {review}")

    if not review:
        print("No review message provided!")
        return jsonify({'error': 'No review message provided'}), 400

    try:
        # Tokenize and vectorize the review for sentiment analysis
        tokenized_review = spacy_tokenizer(review)
        review_tfidf = tfidf_vectorizer.transform([tokenized_review])

        # Predict sentiment (0 for positive, 1 for negative)
        sentiment_prediction = model.predict(review_tfidf)[0]
        sentiment = 'Negative' if sentiment_prediction == 1 else 'Positive'
        print(f"Sentiment prediction: {sentiment}")

        # Use pre-trained hate speech detection model to check for hate speech
        hate_speech_result = hate_speech_detector(review)[0]
        print(f"Hate speech detection result: {hate_speech_result}")

        # Determine if it's hate speech or not based on the label and score
        hate_speech = 'Hate Speech' if hate_speech_result['label'] in ['toxic', 'severe_toxic'] and hate_speech_result['score'] > 0.5 else 'Not Hate Speech'

        print(f"Final Hate Speech classification: {hate_speech}")

        # Return both sentiment and hate speech analysis
        return jsonify({'sentiment': sentiment, 'hate_speech': hate_speech})

    except Exception as e:
        print(f"Error occurred during prediction: {e}")
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    print("Starting Flask app...")
    app.run(debug=True)
