<?php

declare(strict_types=1);

namespace Modules\SystemTool\Ai\Boots;

use Laravel\Boost\Contracts\SupportsGuidelines;
use Laravel\Boost\Contracts\SupportsMcp;
use Laravel\Boost\Contracts\SupportsSkills;
use Laravel\Boost\Install\Agents\Agent;
use Laravel\Boost\Install\Enums\McpInstallationStrategy;
use Laravel\Boost\Install\Enums\Platform;

class Reasonix extends Agent implements SupportsGuidelines, SupportsMcp, SupportsSkills
{
    public function name(): string
    {
        return 'reasonix';
    }

    public function displayName(): string
    {
        return 'Reasonix';
    }

    public function systemDetectionConfig(Platform $platform): array
    {
        return match ($platform) {
            Platform::Darwin, Platform::Linux => [
                'command' => 'command -v reasonix',
            ],
            Platform::Windows => [
                'command' => 'cmd /c where reasonix 2>nul',
            ],
        };
    }

    public function projectDetectionConfig(): array
    {
        return [
            'paths' => ['.reasonix'],
            'files' => ['REASONIX.md'],
        ];
    }

    public function mcpInstallationStrategy(): McpInstallationStrategy
    {
        return McpInstallationStrategy::FILE;
    }

    public function mcpConfigPath(): string
    {
        return config('boost.agents.reasonix.mcp_config_path', '.mcp.json');
    }

    public function guidelinesPath(): string
    {
        return config('boost.agents.reasonix.guidelines_path', 'REASONIX.md');
    }

    public function skillsPath(): string
    {
        return config('boost.agents.reasonix.skills_path', '.reasonix/skills');
    }
}
