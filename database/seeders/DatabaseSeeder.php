<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Keterlambatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Keterlambatan::truncate();
        User::truncate();
        Siswa::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Seed Siswa from CSV
        $this->call([
            SiswaSeeder::class,
        ]);

        // 2. Seed Default Accounts
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@sitelat.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'kelas' => null,
        ]);

        $osis = User::create([
            'name' => 'OSIS Admin',
            'email' => 'osis@sitelat.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'kelas' => null,
        ]);

        $walas1 = User::create([
            'name' => 'Wali Kelas X PPLG 1',
            'email' => 'walas1@sitelat.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'kelas' => 'X PPLG 1',
        ]);

        $walas2 = User::create([
            'name' => 'Wali Kelas X PPLG 2',
            'email' => 'walas2@sitelat.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'kelas' => 'X PPLG 2',
        ]);

        // 3. Seed Mock Keterlambatan matching original dashboard mock data
        $siswaAlmira = Siswa::where('nama', 'ALMIRA KHAERUNISA')->first();
        $siswaBryan = Siswa::where('nama', 'Bryan Rizqulloh')->first();
        $siswaDede = Siswa::where('nama', 'DEDE OKTAVIANI')->first();

        // Almira has 1 lateness
        if ($siswaAlmira) {
            Keterlambatan::create([
                'siswa_id' => $siswaAlmira->id,
                'user_id' => $osis->id,
                'tanggal' => '2026-06-24',
                'alasan' => 'Macet',
                'tahun_ajaran' => '2025/2026',
                'semester' => 'Genap',
                'penanganan' => null,
            ]);
        }

        // Bryan has 3 latenesses
        if ($siswaBryan) {
            Keterlambatan::create([
                'siswa_id' => $siswaBryan->id,
                'user_id' => $osis->id,
                'tanggal' => '2026-06-22',
                'alasan' => 'Kesiangan',
                'tahun_ajaran' => '2025/2026',
                'semester' => 'Genap',
                'penanganan' => null,
            ]);
            Keterlambatan::create([
                'siswa_id' => $siswaBryan->id,
                'user_id' => $osis->id,
                'tanggal' => '2026-06-23',
                'alasan' => 'Ban Bocor',
                'tahun_ajaran' => '2025/2026',
                'semester' => 'Genap',
                'penanganan' => null,
            ]);
            Keterlambatan::create([
                'siswa_id' => $siswaBryan->id,
                'user_id' => $osis->id,
                'tanggal' => '2026-06-24',
                'alasan' => 'Macet',
                'tahun_ajaran' => '2025/2026',
                'semester' => 'Genap',
                'penanganan' => null,
            ]);
        }

        // Dede has 5 latenesses
        if ($siswaDede) {
            Keterlambatan::create([
                'siswa_id' => $siswaDede->id,
                'user_id' => $osis->id,
                'tanggal' => '2026-06-20',
                'alasan' => 'Macet',
                'tahun_ajaran' => '2025/2026',
                'semester' => 'Genap',
                'penanganan' => 'Teguran lisan oleh walas',
            ]);
            Keterlambatan::create([
                'siswa_id' => $siswaDede->id,
                'user_id' => $osis->id,
                'tanggal' => '2026-06-21',
                'alasan' => 'Kesiangan',
                'tahun_ajaran' => '2025/2026',
                'semester' => 'Genap',
                'penanganan' => 'Teguran lisan kedua',
            ]);
            Keterlambatan::create([
                'siswa_id' => $siswaDede->id,
                'user_id' => $osis->id,
                'tanggal' => '2026-06-22',
                'alasan' => 'Kesiangan',
                'tahun_ajaran' => '2025/2026',
                'semester' => 'Genap',
                'penanganan' => null,
            ]);
            Keterlambatan::create([
                'siswa_id' => $siswaDede->id,
                'user_id' => $osis->id,
                'tanggal' => '2026-06-23',
                'alasan' => 'Macet',
                'tahun_ajaran' => '2025/2026',
                'semester' => 'Genap',
                'penanganan' => null,
            ]);
            Keterlambatan::create([
                'siswa_id' => $siswaDede->id,
                'user_id' => $osis->id,
                'tanggal' => '2026-06-24',
                'alasan' => 'Kesiangan',
                'tahun_ajaran' => '2025/2026',
                'semester' => 'Genap',
                'penanganan' => null,
            ]);
        }
    }
}
