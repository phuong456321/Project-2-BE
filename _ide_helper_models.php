<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int|null $parents_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Author> $authors
 * @property-read int|null $authors_count
 * @property-read Area|null $parents
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Song> $songs
 * @property-read int|null $songs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereParentsId($value)
 */
	class Area extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $author_name
 * @property string $bio
 * @property int $img_id
 * @property int $area_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Area $area
 * @property-read \App\Models\Image $image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Song> $songs
 * @property-read int|null $songs_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author whereAuthorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author whereImgId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Author whereUpdatedAt($value)
 */
	class Author extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $song_id
 * @property int $user_id
 * @property string $cmt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Song $song
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereCmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment whereUserId($value)
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $recently_id
 * @property int $song_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RecentlyPlayed $recentlyPlayed
 * @property-read \App\Models\Song $song
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailsPlayed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailsPlayed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailsPlayed query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailsPlayed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailsPlayed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailsPlayed whereRecentlyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailsPlayed whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailsPlayed whereUpdatedAt($value)
 */
	class DetailsPlayed extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int|null $parents_id
 * @property-read Genre|null $parents
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Song> $songs
 * @property-read int|null $songs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre whereParentsId($value)
 */
	class Genre extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $google_id
 * @property string $email
 * @property string $name
 * @property int $avatar_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Image $image
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleAccount whereAvatarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleAccount whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleAccount whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleAccount whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoogleAccount whereUpdatedAt($value)
 */
	class GoogleAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $img_id
 * @property string $img_name
 * @property string $img_path
 * @property string $category
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Author> $authors
 * @property-read int|null $authors_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GoogleAccount> $googleAccounts
 * @property-read int|null $google_accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Song> $songs
 * @property-read int|null $songs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\ImageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereImgId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereImgName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereImgPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereUpdatedAt($value)
 */
	class Image extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $playlist_id
 * @property int $song_id
 * @property string $created_at
 * @property-read \App\Models\Playlist $playlist
 * @property-read \App\Models\Song $song
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InPlaylist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InPlaylist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InPlaylist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InPlaylist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InPlaylist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InPlaylist wherePlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InPlaylist whereSongId($value)
 */
	class InPlaylist extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $stripe_payment_intent_id
 * @property string|null $stripe_checkout_session_id
 * @property string|null $momo_transaction_id
 * @property string|null $momo_payment_request_id
 * @property string|null $momo_status
 * @property string $transaction_id
 * @property string $amount
 * @property string $currency
 * @property string $status
 * @property string $payment_method
 * @property string $payment_status
 * @property int $product_id
 * @property int $quantity
 * @property string $price
 * @property string|null $tax_amount
 * @property string|null $fee_amount
 * @property string $total_amount
 * @property string|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereFeeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMomoPaymentRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMomoStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMomoTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStripeCheckoutSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStripePaymentIntentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUserId($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InPlaylist> $inPlaylists
 * @property-read int|null $in_playlists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Song> $songs
 * @property-read int|null $songs_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Playlist whereUserId($value)
 */
	class Playlist extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $cycles
 * @property float $price
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCycles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailsPlayed> $details
 * @property-read int|null $details_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentlyPlayed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentlyPlayed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentlyPlayed query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentlyPlayed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentlyPlayed whereUserId($value)
 */
	class RecentlyPlayed extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $content
 * @property int|null $clicked_song_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereClickedSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SearchHistory whereUserId($value)
 */
	class SearchHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $song_name
 * @property int $author_id
 * @property int $area_id
 * @property int $genre_id
 * @property string $audio_path
 * @property string|null $duration
 * @property int $img_id
 * @property string $status
 * @property int $likes
 * @property int $play_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $lyric
 * @property string|null $lyric_path
 * @property-read \App\Models\Area $area
 * @property-read \App\Models\Author $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Genre $genre
 * @property-read \App\Models\Image $image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InPlaylist> $inPlaylists
 * @property-read int|null $in_playlists_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereAudioPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereImgId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereLyric($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereLyricPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song wherePlayCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereSongName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereUpdatedAt($value)
 */
	class Song extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int|null $author_id
 * @property string $role
 * @property string $plan
 * @property string $status
 * @property string|null $google_id
 * @property int|null $avatar_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $email_verification_token
 * @property \Illuminate\Support\Carbon|null $email_verification_sent_at
 * @property-read \App\Models\Author|null $author
 * @property-read \App\Models\Image|null $avatar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\GoogleAccount|null $googleAccount
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Playlist> $playlists
 * @property-read int|null $playlists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RecentlyPlayed> $recentlyPlayed
 * @property-read int|null $recently_played_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SearchHistory> $searchHistories
 * @property-read int|null $search_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerificationSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerificationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

