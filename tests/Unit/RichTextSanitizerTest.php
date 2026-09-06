<?php

namespace Tests\Unit;

use App\Support\RichTextSanitizer;
use PHPUnit\Framework\TestCase;

class RichTextSanitizerTest extends TestCase
{
    public function test_it_preserves_supported_formatting_and_removes_executable_markup(): void
    {
        $sanitized = (new RichTextSanitizer)->sanitize(
            '<h2 onclick="alert(1)">Headline</h2>'
            .'<p><strong>Safe</strong><script>alert(1)</script></p>'
            .'<a href="javascript:alert(1)" target="_blank">Bad link</a>'
            .'<a href="https://example.com" target="_blank">Good link</a>'
        );

        $this->assertStringContainsString('<h2>Headline</h2>', $sanitized);
        $this->assertStringContainsString('<strong>Safe</strong>', $sanitized);
        $this->assertStringNotContainsString('script', $sanitized);
        $this->assertStringNotContainsString('onclick', $sanitized);
        $this->assertStringNotContainsString('javascript:', $sanitized);
        $this->assertStringContainsString(
            '<a href="https://example.com" target="_blank" rel="noopener noreferrer">Good link</a>',
            $sanitized
        );
    }

    public function test_it_unwraps_unsupported_visual_elements_without_losing_text(): void
    {
        $sanitized = (new RichTextSanitizer)->sanitize(
            '<div class="legacy"><span style="color:red">Legacy text</span></div>'
        );

        $this->assertSame('Legacy text', $sanitized);
    }

    public function test_it_preserves_safe_article_images_and_removes_unsafe_sources(): void
    {
        $sanitized = (new RichTextSanitizer)->sanitize(
            '<p><img src="https://media.example.test/article.jpg" alt="Article" onerror="alert(1)"></p>'
            .'<img src="data:image/png;base64,unsafe">'
            .'<img src="blob:https://example.test/temporary">'
        );

        $this->assertStringContainsString('src="https://media.example.test/article.jpg"', $sanitized);
        $this->assertStringContainsString('loading="lazy"', $sanitized);
        $this->assertStringNotContainsString('onerror', $sanitized);
        $this->assertStringNotContainsString('data:image', $sanitized);
        $this->assertStringNotContainsString('blob:', $sanitized);
    }
}
