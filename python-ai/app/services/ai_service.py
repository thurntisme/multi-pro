from transformers import pipeline

class AIService:
    def __init__(self):
        self.model = pipeline("text-generation", model="gpt2")

    def generate_response(self, keyword: str) -> str:
        result = self.model(f"{keyword}", max_length=50, num_return_sequences=1)
        return result[0]["generated_text"]
