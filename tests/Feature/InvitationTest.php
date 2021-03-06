<?php declare(strict_types=1);

namespace Tests\Feature;

use App\User;
use App\History;
use function route;
use Tests\TestCase;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function acceptInvitation(): void
    {
        $history = History::factory()->create();
        $invitedUser = User::factory()->create();

        $this->actingAs($invitedUser)->get(URL::signedRoute('invitation.accept', [
            'history' => $history->id,
        ]));

        $history->refresh();
        $this->assertTrue($history->isPlayer($invitedUser));
    }

    /** @test */
    public function onlyALoggedInUserCanAcceptAnInvitation(): void
    {
        $history = History::factory()->create();

        $response = $this->get(URL::signedRoute('invitation.accept', [
            'history' => $history->id,
        ]));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function cannotAcceptAnInvitationTwice(): void
    {
        $history = History::factory()->create();
        $invitedUser = User::factory()->create();
        $history->addPlayer($invitedUser);

        $response = $this->actingAs($invitedUser)->get(URL::signedRoute('invitation.accept', [
            'history' => $history->id,
        ]));

        $response->assertSessionHasErrors([
            'invitation' => __('You are already a player in this game')
        ]);
    }

    /** @test */
    public function ownerCannotAcceptInvitationToTheirOwnGame(): void
    {
        $history = History::factory()->create();

        $response = $this->actingAs($history->owner)->get(URL::signedRoute('invitation.accept', [
            'history' => $history->id,
        ]));

        $response->assertSessionHasErrors([
            'invitation' => __('You cannot accept an invitation to your own game')
        ]);
    }

    /** @test */
    public function guestCanJoinPublicGame(): void
    {
        $history = History::factory()->public()->create();

        $response = $this->post(URL::signedRoute('invitation.accept.guest', ['history' => $history->id]), [
            'name' => '::name::',
        ]);

        $response->assertSessionHas('histories', [$history->id => '::name::']);
    }

    /** @test */
    public function cannotAcceptGuestInvitationAsAuthenticatedUser(): void
    {
        $user = User::factory()->create();
        $history = History::factory()->public()->create();

        $response = $this->actingAs($user)
            ->post(URL::signedRoute('invitation.accept.guest', ['history' => $history->id]), [
                'name' => '::name::',
            ]);

        $response->assertForbidden();
    }

    /** @test */
    public function cannotAcceptGuestInvitationForPrivateGame(): void
    {
        $history = History::factory()->create();

        $response = $this->post(URL::signedRoute('invitation.accept.guest', ['history' => $history->id]), [
            'name' => '::name::',
        ]);

        $response->assertForbidden();
    }
}
