from fastapi import Depends, HTTPException
from fastapi.security import OAuth2AuthorizationCodeBearer
from keycloak import KeycloakOpenID
import os

KEYCLOAK_URL = os.getenv("KEYCLOAK_URL", "http://localhost:8080")
KEYCLOAK_REALM = os.getenv("KEYCLOAK_REALM", "fastapi-realm")
KEYCLOAK_CLIENT_ID = os.getenv("KEYCLOAK_CLIENT_ID", "fastapi-client")
KEYCLOAK_CLIENT_SECRET = os.getenv("KEYCLOAK_CLIENT_SECRET", "your-client-secret")

keycloak_openid = KeycloakOpenID(
    server_url=f"{KEYCLOAK_URL}/",
    client_id=KEYCLOAK_CLIENT_ID,
    realm_name=KEYCLOAK_REALM,
    client_secret_key=KEYCLOAK_CLIENT_SECRET,
)

oauth2_scheme = OAuth2AuthorizationCodeBearer(
    authorizationUrl=f"{KEYCLOAK_URL}/realms/{KEYCLOAK_REALM}/protocol/openid-connect/auth",
    tokenUrl=f"{KEYCLOAK_URL}/realms/{KEYCLOAK_REALM}/protocol/openid-connect/token"
)

def get_current_user(token: str = Depends(oauth2_scheme)):
    try:
        user_info = keycloak_openid.userinfo(token)
        if not user_info:
            raise HTTPException(status_code=401, detail="Invalid authentication")
        return user_info
    except:
        raise HTTPException(status_code=401, detail="Invalid authentication")
