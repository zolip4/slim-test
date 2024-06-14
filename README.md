# API Design

## Endpoint

`/optimize_titles`

## Method

`POST`

## Description

This endpoint allows sending data to the server for processing and receiving the result.
Receives a batch of video details and returns optimized titles based on various analysis methods, along with a request counter for the current time period.

## Headers

| Header        | Type    | Description                              | Values                                                            |
|---------------|---------|------------------------------------------|-------------------------------------------------------------------|
| X-API-Key     | String  | The API key for authentication.          | YOUR_API_KEY                                                      |
| X-Checksum    | String  | The cryptographic checksum for request validation. | 1717512605                                                        |
| X-Timestamp   | String  | The timestamp to prevent replay attacks. | a30c073516f9e0fd3b2326fe1f6a7dbc610534838c07b61dc28c70e31df06683  |

## Request Body

```json
{
    "title_length": 60,
    "prioritization_guide": {"tags": 1, "model_names": 2, "orientation": 3},
    "interpretation_type": "channel",
    "videos": [
        {
            "id": "4115976",
            "user_id": "1779238",
            "vkey": "866891845",
            "title": "Original Title",
            "username": "user_name",
            "stagename": "",
            "production": "professional",
            "orientation": "type",
            "categories": "category 1, category 2",
            "tags": "tag 1, tag 2, tag 3",
            "pornstars": "star 1, star 2",
            "attributeNames": "attribute_names",
            "attributes": "attribute_values",
            "actiontags": "tag",
            "views": "14410928"
        }
    ]
}
```
- **title_length:** *Required* - The character length of the title.
- **videos:** *Required* - The list of videos.

## Response Body

```json
{
  "message": "text message"
}
```

## Response Codes

| Code | Description                                                                    | Messages                            |
|------|--------------------------------------------------------------------------------|-------------------------------------|
| 200  | Successful request. Returns the processed result.                              | Data published successfully                                  |
| 400  | Bad request. Incorrect data or missing required parameters.                    | Missing headers / Request expired   |
| 403  | The provided API key is invalid, and the checksum is incorrect. | Invalid API Key / Invalid checksum. |

## Request Example

```bash
curl -X POST \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-API-Key: YOUR_API_KEY" \
  -H "X-Checksum: YOUR_CHECKSUM" \
  -H "X-Timestamp: TIMESTAMP_VALUE" \
  -d '{"title_length": 60, "prioritization_guide": {"tags": 1, "model_names": 2, "orientation": 3}, "interpretation_type": "channel", "videos": [{"id": "4115976", "user_id": "1779238", "vkey": "866891845", "title": "Original Title", "username": "user_name", "stagename": "", "production": "professional", "orientation": "type", "categories": "category 1, category 2", "tags": "tag 1, tag 2, tag 3", "pornstars": "star 1, star 2", "attributeNames": "attribute_names", "attributes": "attribute_values", "actiontags": "tag", "views": "14410928"}]}' \
  http://example.com/optimize_titles
```

## Error Handling

In case of an error, the server will return the corresponding HTTP status code along with a JSON object describing the error. For example:

```json
{
  "message": "text message"
}
```

## Generating `timestamp` and `checksum`

To generate the `timestamp` and `checksum` variables, use the following code:

```php
$secretKey = 'your_secret_key';
$timestamp = time();
$checksum = hash_hmac('sha256', $timestamp . $secretKey, $secretKey);
```

Include the generated `timestamp` and `checksum` values in the request headers `X-Timestamp` and `X-Checksum`, respectively.