<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\Livestream;
use App\Models\Offering;
use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\Testimony;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ────────────────────────────────────────────────────
        $superAdmin = User::create([
            'full_name'         => 'Super Admin',
            'email'             => 'admin@ofcc.org',
            'phone'             => '+234 800 000 0001',
            'password'          => Hash::make('password'),
            'role'              => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
        ]);

        // ── Media Admin ────────────────────────────────────────────────────
        $mediaAdmin = User::create([
            'full_name'         => 'Media Admin',
            'email'             => 'media@ofcc.org',
            'phone'             => '+234 800 000 0002',
            'password'          => Hash::make('password'),
            'role'              => User::ROLE_MEDIA_ADMIN,
            'email_verified_at' => now(),
        ]);

        // ── Sample Members ─────────────────────────────────────────────────
        $members = [
            ['full_name' => 'John Adebayo',   'email' => 'john@example.com',   'phone' => '+234 801 234 5678'],
            ['full_name' => 'Grace Okafor',   'email' => 'grace@example.com',  'phone' => '+234 802 345 6789'],
            ['full_name' => 'Samuel Nwosu',   'email' => 'samuel@example.com', 'phone' => '+234 803 456 7890'],
            ['full_name' => 'Esther Bello',   'email' => 'esther@example.com', 'phone' => '+234 804 567 8901'],
        ];

        foreach ($members as $m) {
            User::create(array_merge($m, [
                'password'          => Hash::make('password'),
                'role'              => User::ROLE_MEMBER,
                'email_verified_at' => now(),
            ]));
        }

        // ── Resource Categories ────────────────────────────────────────────
        $cats = [
            ['name' => 'Training Manuals',    'slug' => 'training-manuals',    'icon' => '📘'],
            ['name' => 'Evangelism Tools',    'slug' => 'evangelism-tools',    'icon' => '✝️'],
            ['name' => 'Sermon Notes',        'slug' => 'sermon-notes',        'icon' => '📝'],
            ['name' => 'Prayer Resources',    'slug' => 'prayer-resources',    'icon' => '🙏'],
            ['name' => 'Media & Graphics',    'slug' => 'media-graphics',      'icon' => '🎨'],
        ];

        foreach ($cats as $cat) {
            ResourceCategory::create($cat);
        }

        $cat1 = ResourceCategory::first();

        // ── Resources ──────────────────────────────────────────────────────
        Resource::create([
            'title'          => 'Evangelism Training Manual Vol. 1',
            'description'    => 'Comprehensive guide for personal and community evangelism.',
            'category_id'    => $cat1->id,
            'file_url'       => 'https://example.com/resources/training-manual.pdf',
            'file_type'      => 'pdf',
            'file_size'      => '2.4 MB',
            'download_count' => 128,
            'is_public'      => true,
            'uploaded_by'    => $superAdmin->id,
        ]);

        Resource::create([
            'title'          => 'Outreach Planning Template',
            'description'    => 'Step-by-step planning template for outreach campaigns.',
            'category_id'    => $cat1->id,
            'file_url'       => 'https://example.com/resources/planning.docx',
            'file_type'      => 'docx',
            'file_size'      => '540 KB',
            'download_count' => 45,
            'is_public'      => true,
            'uploaded_by'    => $mediaAdmin->id,
        ]);

        // ── Livestreams ────────────────────────────────────────────────────
        Livestream::create([
            'title'        => 'Sunday Service — May 2025',
            'description'  => 'Join us for our powerful Sunday service.',
            'stream_url'   => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'platform'     => 'youtube',
            'status'       => 'ended',
            'scheduled_at' => now()->subDays(7),
            'started_at'   => now()->subDays(7),
            'ended_at'     => now()->subDays(7)->addHours(2),
            'created_by'   => $mediaAdmin->id,
        ]);

        Livestream::create([
            'title'        => 'Upcoming: Midweek Outreach Service',
            'description'  => 'Powerful midweek service with special ministrations.',
            'stream_url'   => '',
            'platform'     => 'youtube',
            'status'       => 'scheduled',
            'scheduled_at' => now()->addDays(3),
            'created_by'   => $mediaAdmin->id,
        ]);

        // ── Announcements ──────────────────────────────────────────────────
        Announcement::create([
            'title'        => 'Welcome to OFCC Online Platform! 🎉',
            'content'      => 'We are thrilled to launch our brand new online outreach platform. This is your one-stop hub for live streams, resources, events, and community engagement. Explore all the features and let\'s build the kingdom together!',
            'type'         => 'general',
            'is_pinned'    => true,
            'published_at' => now(),
            'created_by'   => $superAdmin->id,
        ]);

        Announcement::create([
            'title'        => 'Special Outreach Campaign This Saturday',
            'content'      => 'We will be conducting a community outreach at Lagos Island this Saturday at 9am. All members are encouraged to participate. Please register so we can plan logistics.',
            'type'         => 'event',
            'is_pinned'    => false,
            'published_at' => now()->subDays(1),
            'created_by'   => $superAdmin->id,
        ]);

        Announcement::create([
            'title'        => '⚡ Urgent: Prayer Chain for Brother Emmanuel',
            'content'      => 'Please join our emergency prayer chain for Brother Emmanuel who is currently hospitalized. Kindly remember him and his family in your prayers.',
            'type'         => 'urgent',
            'is_pinned'    => false,
            'published_at' => now()->subHours(6),
            'created_by'   => $superAdmin->id,
        ]);

        // ── Events ─────────────────────────────────────────────────────────
        Event::create([
            'title'         => 'Community Evangelism — Lagos Island',
            'description'   => 'Join our dedicated team as we reach out to the community with the gospel message. Refreshments will be provided.',
            'location'      => 'Lagos Island, Lagos State',
            'start_date'    => now()->addDays(5)->setTime(9, 0),
            'end_date'      => now()->addDays(5)->setTime(14, 0),
            'requires_rsvp' => true,
            'max_attendees' => 100,
            'created_by'    => $superAdmin->id,
        ]);

        Event::create([
            'title'         => 'Online Prayer Night',
            'description'   => 'An intense night of prayer and intercession via Zoom. Link will be shared upon RSVP.',
            'location'      => 'Online (Zoom)',
            'start_date'    => now()->addDays(8)->setTime(21, 0),
            'end_date'      => now()->addDays(9)->setTime(2, 0),
            'requires_rsvp' => true,
            'created_by'    => $superAdmin->id,
        ]);

        Event::create([
            'title'         => 'Leadership Training Workshop',
            'description'   => 'Intensive workshop for outreach team leaders. Topics include evangelism strategy, volunteer management, and digital outreach.',
            'location'      => 'OFCC HQ, Victoria Island, Lagos',
            'start_date'    => now()->addDays(14)->setTime(10, 0),
            'end_date'      => now()->addDays(14)->setTime(17, 0),
            'requires_rsvp' => true,
            'max_attendees' => 50,
            'created_by'    => $superAdmin->id,
        ]);

        // ── Offerings ──────────────────────────────────────────────────────
        Offering::create([
            'title'            => 'General Offering',
            'description'      => 'Support the day-to-day operations of our outreach ministry.',
            'account_name'     => 'OFCC Outreach Ministry',
            'account_number'   => '0123456789',
            'bank_name'        => 'First Bank Nigeria',
            'payment_link'     => 'https://paystack.com/pay/ofcc',
            'payment_provider' => 'paystack',
            'is_active'        => true,
        ]);

        Offering::create([
            'title'            => 'Mission Fund',
            'description'      => 'Help us fund evangelism trips and outreach campaigns across Nigeria and beyond.',
            'account_name'     => 'OFCC Mission Fund',
            'account_number'   => '9876543210',
            'bank_name'        => 'Zenith Bank',
            'payment_link'     => 'https://flutterwave.com/pay/ofcc-mission',
            'payment_provider' => 'flutterwave',
            'is_active'        => true,
        ]);

        // ── Testimonies ────────────────────────────────────────────────────
        Testimony::create([
            'author_name' => 'Brother Tunde A.',
            'content'     => 'Through OFCC\'s online resources and live streams, I found faith and purpose. The training manuals helped me lead my first evangelism team. God is truly using this platform!',
            'is_approved' => true,
        ]);

        Testimony::create([
            'author_name' => 'Sister Adaeze M.',
            'content'     => 'I was in a dark place when I stumbled upon the OFCC live stream. The Word spoke directly to my situation. Today I am a volunteer and actively participating in outreach. Praise God!',
            'is_approved' => true,
        ]);

        Testimony::create([
            'author_name' => 'Pastor James O.',
            'content'     => 'The resources available on this platform have transformed how we do ministry in our local church. The evangelism manual alone has equipped over 30 of our members.',
            'is_approved' => true,
        ]);
    }
}
