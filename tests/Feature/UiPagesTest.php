<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UiPagesTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, array{string, string}> */
    public static function adminPages(): array
    {
        return [
            'users' => ['admin.users.index', 'admin/users/Index'],
            'roles' => ['admin.roles.index', 'admin/roles/Index'],
            'menus' => ['admin.menus.index', 'admin/menus/Index'],
            'menu access' => ['admin.menu-access.index', 'admin/menu-access/Index'],
            'carousels' => ['admin.carousels.index', 'admin/carousels/Index'],
            'news categories' => ['admin.news-categories.index', 'admin/news-categories/Index'],
            'news' => ['admin.news.index', 'admin/news/Index'],
            'about' => ['admin.about.edit', 'admin/about/Edit'],
            'system settings' => ['admin.system-settings.index', 'admin/system-settings/Index'],
            'logs' => ['admin.logs.index', 'admin/logs/Index'],
            'failed jobs' => ['admin.failed-jobs.index', 'admin/failed-jobs/Index'],
        ];
    }

    #[DataProvider('adminPages')]
    public function test_admin_pages_render_for_authenticated_users(string $route, string $component): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route($route))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component($component));
    }

    #[DataProvider('adminPages')]
    public function test_admin_pages_redirect_guests_to_login(string $route): void
    {
        $this->get(route($route))->assertRedirect(route('login'));
    }

    public function test_public_website_pages_render(): void
    {
        $this->get(route('home'))->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('website/Home')->has('slides', 3));

        $this->get(route('news.index', ['category' => 'panduan']))->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('website/news/Index')->has('news', 2));

        $this->get(route('news.show', 'panduan-alur-sertifikasi-dari-pendaftaran-sampai-sertifikat'))->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('website/news/Show'));

        $this->get(route('about'))->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('website/About'));
    }

    public function test_scheme_pages_filter_and_resolve(): void
    {
        $this->get(route('schemes.index'))->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('website/schemes/Index')->has('schemes', 6));

        $this->get(route('schemes.index', ['field' => 'teknologi-informasi']))->assertOk()
            ->assertInertia(fn (Assert $page) => $page->has('schemes', 2));

        $this->get(route('schemes.index', ['level' => 3]))->assertOk()
            ->assertInertia(fn (Assert $page) => $page->has('schemes', 4)->has('levels', 9));

        $this->get(route('schemes.index', ['q' => 'tidak-ada-sama-sekali']))->assertOk()
            ->assertInertia(fn (Assert $page) => $page->has('schemes', 0));

        $this->get(route('schemes.show', 'pemasaran-digital'))->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('website/schemes/Show'));

        $this->get(route('schemes.show', 'tidak-ada'))->assertNotFound();
    }

    public function test_draft_and_unknown_articles_return_not_found(): void
    {
        $this->get(route('news.show', 'pendaftaran-asesor-baru'))->assertNotFound();
        $this->get(route('news.show', 'tidak-ada'))->assertNotFound();
    }
}
