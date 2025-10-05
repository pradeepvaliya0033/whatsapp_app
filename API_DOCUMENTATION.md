# WhatsApp Business API Provider - API Documentation

## Overview

This WhatsApp Business API Provider is a comprehensive solution similar to WATI that allows you to manage WhatsApp messaging, templates, and track message delivery through a RESTful API.

## Base URL

```
https://your-domain.com/api
```

## Authentication

All API endpoints require authentication. Include your authentication token in the request headers:

```
Authorization: Bearer your-token-here
```

## API Endpoints

### Entity Management

#### Create Entity

```http
POST /api/entities
Content-Type: application/json

{
    "name": "My Company",
    "description": "Company description",
    "status": true
}
```

**Response:**

```json
{
    "status": "success",
    "data": {
        "id": 1,
        "uuid": "550e8400-e29b-41d4-a716-446655440000",
        "name": "My Company",
        "description": "Company description",
        "status": true,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    },
    "message": "Entity created successfully"
}
```

#### Get All Entities

```http
GET /api/entities?status=true
```

#### Get Entity by UUID

```http
GET /api/entities/{uuid}
```

#### Update Entity

```http
PUT /api/entities/{uuid}
Content-Type: application/json

{
    "name": "Updated Company Name",
    "description": "Updated description"
}
```

#### Delete Entity

```http
DELETE /api/entities/{uuid}
```

#### Add Provider to Entity

```http
POST /api/entities/{uuid}/providers
Content-Type: application/json

{
    "provider_id": "provider-uuid",
    "usage_type": "WhatsApp",
    "is_default": true
}
```

### Provider Management

#### Create Provider

```http
POST /api/providers
Content-Type: application/json

{
    "name": "WhatsApp Business Provider",
    "description": "Primary WhatsApp provider",
    "provider_type": "WhatsApp",
    "api_config": {
        "api_url": "https://graph.facebook.com/v18.0",
        "phone_number_id": "your_phone_number_id",
        "access_token": "your_access_token",
        "business_account_id": "your_business_account_id",
        "app_secret": "your_app_secret"
    },
    "status": true
}
```

#### Get All Providers

```http
GET /api/providers?provider_type=WhatsApp&status=true
```

#### Get Provider by UUID

```http
GET /api/providers/{uuid}
```

#### Update Provider

```http
PUT /api/providers/{uuid}
Content-Type: application/json

{
    "name": "Updated Provider Name",
    "api_config": {
        "api_url": "https://graph.facebook.com/v18.0",
        "phone_number_id": "new_phone_number_id",
        "access_token": "new_access_token",
        "business_account_id": "new_business_account_id",
        "app_secret": "new_app_secret"
    }
}
```

#### Delete Provider

```http
DELETE /api/providers/{uuid}
```

#### Test Provider Configuration

```http
POST /api/providers/{uuid}/test
```

### WhatsApp Messaging

#### Send Template Message

```http
POST /api/whatsapp/send-message
Content-Type: application/json

{
    "entity_id": "entity-uuid",
    "provider_id": "provider-uuid",
    "to": ["+1234567890", "+0987654321"],
    "template_name": "welcome_template",
    "lang_code": "en_US",
    "parameters": ["John", "12345"]
}
```

**Response:**

```json
{
    "status": "success",
    "message_request_id": "msg_550e8400e29b41d4a716446655440000",
    "responses": [
        {
            "messaging_product": "whatsapp",
            "contacts": [
                {
                    "input": "+1234567890",
                    "wa_id": "1234567890"
                }
            ],
            "messages": [
                {
                    "id": "wamid.HBgLMTIzNDU2Nzg5MDEwFQIAEhggNzY4NzQ2NzQ2NzQ2NzQ2NzQ2NzQ2NzQ2NzQ2NzQ2NzQ="
                }
            ]
        }
    ]
}
```

#### Send Text Message

```http
POST /api/whatsapp/send-text
Content-Type: application/json

{
    "entity_id": "entity-uuid",
    "provider_id": "provider-uuid",
    "to": "+1234567890",
    "message": "Hello, this is a test message!"
}
```

### Template Management

#### Create Template

```http
POST /api/whatsapp/templates
Content-Type: application/json

{
    "name": "welcome_template",
    "category": "TRANSACTIONAL",
    "lang_code": "en_US",
    "header": {
        "type": "text",
        "text": "Welcome!"
    },
    "body": "Hello {{1}}, your verification code is {{2}}",
    "footer": "Thank you for using our service",
    "buttons": [
        {
            "type": "quick_reply",
            "text": "Get Started"
        },
        {
            "type": "url",
            "text": "Visit Website",
            "url": "https://example.com"
        }
    ]
}
```

**Response:**

```json
{
    "status": "success",
    "data": {
        "id": "1234567890123456",
        "status": "PENDING",
        "category": "TRANSACTIONAL",
        "name": "welcome_template",
        "language": "en_US",
        "components": [
            {
                "type": "HEADER",
                "format": "TEXT",
                "text": "Welcome!"
            },
            {
                "type": "BODY",
                "text": "Hello {{1}}, your verification code is {{2}}"
            },
            {
                "type": "FOOTER",
                "text": "Thank you for using our service"
            },
            {
                "type": "BUTTONS",
                "buttons": [
                    {
                        "type": "QUICK_REPLY",
                        "text": "Get Started"
                    },
                    {
                        "type": "URL",
                        "text": "Visit Website",
                        "url": "https://example.com"
                    }
                ]
            }
        ]
    }
}
```

#### Get All Templates

```http
GET /api/whatsapp/templates
```

#### Get Template by ID

```http
GET /api/whatsapp/templates/{template_id}
```

#### Delete Template

```http
DELETE /api/whatsapp/templates/{template_id}
```

### Message Status & History

#### Get Message Status

```http
GET /api/whatsapp/messages/{message_id}/status
```

**Response:**

```json
{
    "status": "success",
    "data": {
        "messaging_product": "whatsapp",
        "contacts": [
            {
                "input": "+1234567890",
                "wa_id": "1234567890"
            }
        ],
        "messages": [
            {
                "id": "wamid.HBgLMTIzNDU2Nzg5MDEwFQIAEhggNzY4NzQ2NzQ2NzQ2NzQ2NzQ2NzQ2NzQ2NzQ2NzQ2NzQ=",
                "status": "delivered",
                "timestamp": "1640995200"
            }
        ]
    }
}
```

#### Get Message Requests

```http
GET /api/whatsapp/messages?entity_id=entity-uuid&status=sent&limit=20&page=1
```

**Response:**

```json
{
    "status": "success",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "uuid": "msg_550e8400e29b41d4a716446655440000",
                "transition_number": "msg_550e8400e29b41d4a716446655440000",
                "entity_id": 1,
                "provider_id": 1,
                "type": "WhatsApp",
                "message_config": {
                    "api_url": "https://graph.facebook.com/v18.0",
                    "phone_number_id": "your_phone_number_id"
                },
                "message_count": 1,
                "status": "sent",
                "request": {
                    "entity_id": "entity-uuid",
                    "to": ["+1234567890"],
                    "template_name": "welcome_template"
                },
                "response": [
                    {
                        "messaging_product": "whatsapp",
                        "contacts": [
                            {
                                "input": "+1234567890",
                                "wa_id": "1234567890"
                            }
                        ]
                    }
                ],
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "first_page_url": "http://localhost/api/whatsapp/messages?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://localhost/api/whatsapp/messages?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost/api/whatsapp/messages?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http://localhost/api/whatsapp/messages",
        "per_page": 20,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

## Error Responses

All endpoints return consistent error responses:

```json
{
    "status": "error",
    "message": "Error description",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

## Status Codes

-   `200` - Success
-   `201` - Created
-   `400` - Bad Request (validation errors)
-   `401` - Unauthorized
-   `404` - Not Found
-   `500` - Internal Server Error

## Rate Limiting

The API implements rate limiting to prevent abuse. Rate limit headers are included in responses:

```
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1640995200
```

## Webhooks

Configure webhooks to receive real-time updates about message status changes:

```http
POST /api/whatsapp/webhook
Content-Type: application/json

{
    "object": "whatsapp_business_account",
    "entry": [
        {
            "id": "business_account_id",
            "changes": [
                {
                    "value": {
                        "messaging_product": "whatsapp",
                        "metadata": {
                            "display_phone_number": "1234567890",
                            "phone_number_id": "phone_number_id"
                        },
                        "statuses": [
                            {
                                "id": "message_id",
                                "status": "delivered",
                                "timestamp": "1640995200",
                                "recipient_id": "1234567890"
                            }
                        ]
                    },
                    "field": "messages"
                }
            ]
        }
    ]
}
```

## SDK Examples

### PHP

```php
use GuzzleHttp\Client;

$client = new Client(['base_uri' => 'https://your-domain.com/api']);

// Send a template message
$response = $client->post('/whatsapp/send-message', [
    'json' => [
        'entity_id' => 'entity-uuid',
        'to' => ['+1234567890'],
        'template_name' => 'welcome_template',
        'lang_code' => 'en_US',
        'parameters' => ['John', '12345']
    ],
    'headers' => [
        'Authorization' => 'Bearer your-token',
        'Content-Type' => 'application/json'
    ]
]);

$data = json_decode($response->getBody(), true);
```

### JavaScript/Node.js

```javascript
const axios = require("axios");

const client = axios.create({
    baseURL: "https://your-domain.com/api",
    headers: {
        Authorization: "Bearer your-token",
        "Content-Type": "application/json",
    },
});

// Send a template message
const response = await client.post("/whatsapp/send-message", {
    entity_id: "entity-uuid",
    to: ["+1234567890"],
    template_name: "welcome_template",
    lang_code: "en_US",
    parameters: ["John", "12345"],
});

console.log(response.data);
```

### Python

```python
import requests

base_url = 'https://your-domain.com/api'
headers = {
    'Authorization': 'Bearer your-token',
    'Content-Type': 'application/json'
}

# Send a template message
response = requests.post(f'{base_url}/whatsapp/send-message',
    json={
        'entity_id': 'entity-uuid',
        'to': ['+1234567890'],
        'template_name': 'welcome_template',
        'lang_code': 'en_US',
        'parameters': ['John', '12345']
    },
    headers=headers
)

print(response.json())
```

## Support

For technical support and questions, please refer to the comprehensive README file or contact the development team.
