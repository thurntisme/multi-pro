from fastapi import APIRouter, Depends
from app.models.ai_model import AIRequest, AIResponse
from app.services.ai_service import AIService
from app.repositories.ai_repository import AIRepository
from app.core.security import get_current_user

router = APIRouter()
ai_service = AIService()
ai_repository = AIRepository()

@router.post("/generate/", response_model=AIResponse)
def generate_text(request: AIRequest, user: dict = Depends(get_current_user)):
    response = ai_service.generate_response(request.keyword)
    ai_repository.save_query(request.keyword, user["preferred_username"])
    return AIResponse(message=response, user=user["preferred_username"])
