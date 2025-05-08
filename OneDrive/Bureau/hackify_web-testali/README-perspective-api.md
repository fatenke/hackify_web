# Perspective API Integration

This document describes how to set up and use the Google Perspective API for content moderation in the chat application.

## What is Perspective API?

Perspective API uses machine learning models to identify toxic comments. The API returns a score from 0-1 for different attributes:

- TOXICITY: Rude, disrespectful, or unreasonable comment that is likely to make people leave a discussion.
- SEVERE_TOXICITY: A very toxic comment.
- IDENTITY_ATTACK: Negative or hateful comments targeting someone's identity.
- INSULT: Insulting, inflammatory, or negative comment towards a person or a group of people.
- PROFANITY: Swear words, curse words, or other obscene or profane language.
- THREAT: Describes an intention to inflict pain, injury, or violence against an individual or group.

## Setup Instructions

1. **Get a Perspective API Key**:
   - Visit the [Perspective API website](https://perspectiveapi.com/)
   - Click on "Get started" and follow the instructions to request API access
   - Create a project in Google Cloud Console
   - Enable the Perspective Comment Analyzer API
   - Create an API key

2. **Configure API Key in the Application**:
   - Update the `perspective_api_key` parameter in `config/services.yaml`
   - Replace `'YOUR_PERSPECTIVE_API_KEY_HERE'` with your actual API key
   
   ```yaml
   parameters:
       perspective_api_key: 'YOUR_ACTUAL_API_KEY_HERE'
   ```

3. **Production Environment**:
   - For production, set the API key in your environment variables
   - Update `.env.local` or server environment configuration

## How It Works

The Perspective API is integrated in the following places:

1. **ChatController**: Both `newMessage` and `newGeminiMessage` methods validate message content
2. **MessageController**: The `new` method checks messages for toxic content

When a user sends a message, the application:
1. Captures the message content
2. Sends it to Perspective API for analysis
3. Checks if any toxicity attributes exceed their thresholds
4. Either allows the message to be saved or rejects it with an error

## Customizing Thresholds

The default thresholds for toxic content are:

```php
[
    'TOXICITY' => 0.7,
    'SEVERE_TOXICITY' => 0.5,
    'IDENTITY_ATTACK' => 0.5,
    'INSULT' => 0.7,
    'PROFANITY' => 0.7,
    'THREAT' => 0.5
]
```

You can customize these thresholds by calling the `setThresholds()` method on the `PerspectiveApiService`.

```php
$perspectiveApiService->setThresholds([
    'TOXICITY' => 0.8,  // More permissive
    'PROFANITY' => 0.6  // More strict
]);
```

## Error Handling

If the Perspective API is unavailable or returns an error, messages will be allowed through by default to prevent disruption of the chat functionality. Errors are logged but don't block message posting.

## Additional Notes

- API requests may add slight latency to message sending
- Consider implementing caching for repeated toxic phrase detection
- The service supports multiple languages but uses English ('en') by default 