<?php

return [
    'default' => env('AI_DEFAULT_PROVIDER', 'gemini'),

    'failover' => explode(',', env('AI_FAILOVER_CHAIN', 'gemini,openai,anthropic,groq,ollama')),

    'providers' => [
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
        ],
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        ],
        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-3-5-sonnet-20241022'),
        ],
        'groq' => [
            'api_key' => env('GROQ_API_KEY'),
            'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
        ],
        'ollama' => [
            'base_url' => env('OLLAMA_BASE_URL', 'http://localhost:11434'),
            'model' => env('OLLAMA_MODEL', 'llama3.2:latest'),
        ],
    ],

    'features' => [
        'report_summarizer' => (bool) env('AI_ENABLE_REPORT_SUMMARIZATION', true),
        'critical_flagging' => (bool) env('AI_ENABLE_CRITICAL_VALUE_FLAGGING', true),
        'ocr_requisition' => (bool) env('AI_ENABLE_OCR_REQUISITION', true),
        'doctor_copilot' => (bool) env('AI_ENABLE_DOCTOR_COPILOT', true),
        'patient_chat' => (bool) env('AI_ENABLE_PATIENT_CHAT', true),
    ],

    'cost_tracking' => [
        'enabled' => true,
        'table' => 'ai_logs',
    ],
];
