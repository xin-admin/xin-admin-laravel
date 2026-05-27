/**
 * AI Feature
 */
export type AiFeature = 'default';

/**
 * AI lab
 */
export type AiLab = 'anthropic'| 'azure'| 'bedrock'| 'cohere'| 'deepseek'| 'eleven'| 'gemini'| 'groq'| 'jina'| 'mistral'| 'ollama'| 'openai'| 'openrouter'| 'voyageai'| 'xai';

/**
 * Ai List
 */
export type AiList = Record<AiFeature, string[]>;
