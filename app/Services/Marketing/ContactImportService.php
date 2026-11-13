<?php

namespace App\Services\Marketing;

use App\Models\MarketingContact;
use App\Models\MarketingSuppression;
use App\Models\MarketingConsentLog;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ContactImportService
{
    /**
     * Import contacts from CSV file with duplicate prevention and suppression checking
     */
    public function importCsv(UploadedFile $file, string $defaultConsentSource = 'CSV Import', bool $explicitConsentConfirmed = false, ?string $ip = null): array
    {
        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return ['success' => false, 'imported' => 0, 'skipped' => 0, 'errors' => ['Unable to open CSV file.']];
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return ['success' => false, 'imported' => 0, 'skipped' => 0, 'errors' => ['CSV file is empty.']];
        }

        // Normalize header keys
        $headerMap = [];
        foreach ($header as $index => $colName) {
            $cleaned = strtolower(trim($colName));
            $headerMap[$cleaned] = $index;
        }

        $imported = 0;
        $skipped = 0;
        $suppressedSkipped = 0;
        $errors = [];
        $rowNum = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            if (empty(array_filter($row))) {
                continue; // skip blank rows
            }

            // Extract fields
            $email = $this->extractField($row, $headerMap, ['email', 'e-mail', 'email_address']);
            $name = $this->extractField($row, $headerMap, ['name', 'full_name', 'contact_name', 'contact']);
            $company = $this->extractField($row, $headerMap, ['company', 'company_name', 'firm', 'practice']);
            $website = $this->extractField($row, $headerMap, ['website', 'site', 'url', 'web']);
            $phone = $this->extractField($row, $headerMap, ['phone', 'telephone', 'mobile', 'tel']);
            $businessType = $this->extractField($row, $headerMap, ['business_type', 'industry', 'category', 'type', 'profession']);

            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                $errors[] = "Row {$rowNum}: Invalid email format ({$email}).";
                continue;
            }

            $normalizedEmail = strtolower(trim($email));

            // CRITICAL CHECK: Never re-add unsubscribed or hard-bounced contacts
            if (MarketingSuppression::isSuppressed($normalizedEmail)) {
                $suppressedSkipped++;
                $skipped++;
                continue;
            }

            // Check if existing contact is already unsubscribed
            $existing = MarketingContact::where('email', $normalizedEmail)->first();
            if ($existing && $existing->subscription_status === 'unsubscribed') {
                $skipped++;
                continue;
            }

            $contactName = $name ?: explode('@', $normalizedEmail)[0];

            $contact = MarketingContact::updateOrCreate(
                ['email' => $normalizedEmail],
                [
                    'name' => $contactName,
                    'company' => $company ?: ($existing ? $existing->company : null),
                    'website' => $website ?: ($existing ? $existing->website : null),
                    'phone' => $phone ?: ($existing ? $existing->phone : null),
                    'business_type' => $businessType ?: ($existing ? $existing->business_type : 'Architect / Specifier'),
                    'verification_status' => 'verified',
                    'consent_status' => $explicitConsentConfirmed,
                    'consent_source' => $defaultConsentSource,
                    'consent_date' => now(),
                    'consent_ip' => $ip,
                    'subscription_status' => 'subscribed',
                ]
            );

            // Log consent
            MarketingConsentLog::create([
                'contact_id' => $contact->id,
                'email' => $normalizedEmail,
                'action' => 'opt_in',
                'consent_source' => $defaultConsentSource,
                'ip_address' => $ip,
                'notes' => 'Imported via CSV file ' . $file->getClientOriginalName(),
            ]);

            $imported++;
        }

        fclose($handle);

        return [
            'success' => true,
            'imported' => $imported,
            'skipped' => $skipped,
            'suppressed_skipped' => $suppressedSkipped,
            'errors' => array_slice($errors, 0, 10),
        ];
    }

    protected function extractField(array $row, array $headerMap, array $possibleKeys): ?string
    {
        foreach ($possibleKeys as $key) {
            if (isset($headerMap[$key]) && isset($row[$headerMap[$key]])) {
                $val = trim($row[$headerMap[$key]]);
                if (!empty($val)) {
                    return $val;
                }
            }
        }
        return null;
    }
}
