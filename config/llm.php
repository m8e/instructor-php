<?php

use Cognesy\Instructor\Extras\LLM\Enums\LLMProviderType;
use Cognesy\Instructor\Utils\Env;

return [
    'useObjectReferences' => false,
    'defaultConnection' => 'openai',

    'connections' => [
        'anthropic' => [
            'providerType' => LLMProviderType::Anthropic->value,
            'apiUrl' => 'https://api.anthropic.com/v1',
            'apiKey' => Env::get('ANTHROPIC_API_KEY', ''),
            'endpoint' => '/messages',
            'metadata' => [
                'apiVersion' => '2023-06-01',
                'beta' => 'prompt-caching-2024-07-31',
            ],
            'defaultModel' => 'claude-3-haiku-20240307',
            'defaultMaxTokens' => 1024,
        ],
        'azure' => [
            'providerType' => LLMProviderType::Azure->value,
            'apiUrl' => 'https://{resourceName}.openai.azure.com/openai/deployments/{deploymentId}',
            'apiKey' => Env::get('AZURE_OPENAI_API_KEY', ''),
            'endpoint' => '/chat/completions',
            'metadata' => [
                'apiVersion' => '2023-03-15-preview',
                'resourceName' => 'instructor-dev',
                'deploymentId' => 'gpt-4o-mini',
            ],
            'defaultModel' => 'gpt-4o-mini',
            'defaultMaxTokens' => 1024,
        ],
        'cohere1' => [
            'providerType' => LLMProviderType::CohereV1->value,
            'apiUrl' => 'https://api.cohere.ai/v1',
            'apiKey' => Env::get('COHERE_API_KEY', ''),
            'endpoint' => '/chat',
            'defaultModel' => 'command-r-plus-08-2024',
            'defaultMaxTokens' => 1024,
        ],
        'cohere2' => [
            'providerType' => LLMProviderType::CohereV2->value,
            'apiUrl' => 'https://api.cohere.ai/v2',
            'apiKey' => Env::get('COHERE_API_KEY', ''),
            'endpoint' => '/chat',
            'defaultModel' => 'command-r-plus-08-2024',
            'defaultMaxTokens' => 1024,
        ],
        'fireworks' => [
            'providerType' => LLMProviderType::Fireworks->value,
            'apiUrl' => 'https://api.fireworks.ai/inference/v1',
            'apiKey' => Env::get('FIREWORKS_API_KEY', ''),
            'endpoint' => '/chat/completions',
            'defaultModel' => 'accounts/fireworks/models/mixtral-8x7b-instruct',
            'defaultMaxTokens' => 1024,
        ],
        'gemini' => [
            'providerType' => LLMProviderType::Gemini->value,
            'apiUrl' => 'https://generativelanguage.googleapis.com/v1beta',
            'apiKey' => Env::get('GEMINI_API_KEY', ''),
            'endpoint' => '/models/{model}:generateContent',
            'defaultModel' => 'gemini-1.5-flash-latest',
            'defaultMaxTokens' => 1024,
        ],
        'groq' => [
            'providerType' => LLMProviderType::Groq->value,
            'apiUrl' => 'https://api.groq.com/openai/v1',
            'apiKey' => Env::get('GROQ_API_KEY', ''),
            'endpoint' => '/chat/completions',
            'defaultModel' => 'llama3-groq-8b-8192-tool-use-preview', // 'gemma2-9b-it',
            'defaultMaxTokens' => 1024,
        ],
        'mistral' => [
            'providerType' => LLMProviderType::Mistral->value,
            'apiUrl' => 'https://api.mistral.ai/v1',
            'apiKey' => Env::get('MISTRAL_API_KEY', ''),
            'endpoint' => '/chat/completions',
            'defaultModel' => 'mistral-small-latest',
            'defaultMaxTokens' => 1024,
        ],
        'ollama' => [
            'providerType' => LLMProviderType::Ollama->value,
            'apiUrl' => 'http://localhost:11434/v1',
            'apiKey' => Env::get('OLLAMA_API_KEY', ''),
            'endpoint' => '/chat/completions',
            'defaultModel' => 'llama3.2:3b', //'gemma2:2b',
            'defaultMaxTokens' => 1024,
        ],
        'openai' => [
            'providerType' => LLMProviderType::OpenAI->value,
            'apiUrl' => 'https://api.openai.com/v1',
            'apiKey' => Env::get('OPENAI_API_KEY', ''),
            'endpoint' => '/chat/completions',
            'metadata' => [
                'organization' => '',
                'project' => '',
            ],
            'defaultModel' => 'gpt-4o-mini',
            'defaultMaxTokens' => 1024,
        ],
        'openrouter' => [
            'providerType' => LLMProviderType::OpenRouter->value,
            'apiUrl' => 'https://openrouter.ai/api/v1/',
            'apiKey' => Env::get('OPENROUTER_API_KEY', ''),
            'endpoint' => '/chat/completions',
            'defaultModel' => 'qwen/qwen-2.5-72b-instruct', //'microsoft/phi-3.5-mini-128k-instruct',
            'defaultMaxTokens' => 1024,
        ],
        'together' => [
            'providerType' => LLMProviderType::Together->value,
            'apiUrl' => 'https://api.together.xyz/v1',
            'apiKey' => Env::get('TOGETHER_API_KEY', ''),
            'endpoint' => '/chat/completions',
            'defaultModel' => 'mistralai/Mixtral-8x7B-Instruct-v0.1',
            'defaultMaxTokens' => 1024,
        ],
    ],
];