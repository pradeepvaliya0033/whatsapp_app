<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EntityMaster;
use App\Models\ProviderMaster;
use App\Models\EntityProviderMapping;

class WhatsAppProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample entities
        $entity1 = EntityMaster::create([
            'name' => 'Demo Company',
            'description' => 'Demo company for testing WhatsApp integration',
            'status' => true,
        ]);

        $entity2 = EntityMaster::create([
            'name' => 'Test Organization',
            'description' => 'Test organization for WhatsApp messaging',
            'status' => true,
        ]);

        // Create sample providers
        $provider1 = ProviderMaster::create([
            'name' => 'WhatsApp Business Primary',
            'description' => 'Primary WhatsApp Business API provider',
            'provider_type' => 'WhatsApp',
            'api_config' => [
                'api_url' => 'https://graph.facebook.com/v18.0',
                'phone_number_id' => 'your_phone_number_id',
                'access_token' => 'your_access_token',
                'business_account_id' => 'your_business_account_id',
                'app_secret' => 'your_app_secret'
            ],
            'status' => true,
        ]);

        $provider2 = ProviderMaster::create([
            'name' => 'WhatsApp Business Secondary',
            'description' => 'Secondary WhatsApp Business API provider for backup',
            'provider_type' => 'WhatsApp',
            'api_config' => [
                'api_url' => 'https://graph.facebook.com/v18.0',
                'phone_number_id' => 'your_backup_phone_number_id',
                'access_token' => 'your_backup_access_token',
                'business_account_id' => 'your_backup_business_account_id',
                'app_secret' => 'your_backup_app_secret'
            ],
            'status' => true,
        ]);

        // Create entity-provider mappings
        EntityProviderMapping::create([
            'entity_id' => $entity1->id,
            'provider_id' => $provider1->id,
            'usage_type' => 'WhatsApp',
            'is_default' => true,
            'status' => true,
        ]);

        EntityProviderMapping::create([
            'entity_id' => $entity1->id,
            'provider_id' => $provider2->id,
            'usage_type' => 'WhatsApp',
            'is_default' => false,
            'status' => true,
        ]);

        EntityProviderMapping::create([
            'entity_id' => $entity2->id,
            'provider_id' => $provider1->id,
            'usage_type' => 'WhatsApp',
            'is_default' => true,
            'status' => true,
        ]);

        $this->command->info('WhatsApp Provider sample data seeded successfully!');
        $this->command->info('Created 2 entities and 2 providers with mappings.');
    }
}
