<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DataForSeoController extends Controller
{
    public function index()
    {
        return view('search');
    }

    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'language' => 'required|string|max:10',
        ]);

        $keyword = $request->input('keyword');
        $domain = $request->input('domain');
        $location = $request->input('location');
        $language = $request->input('language');

        try {
            $locationCode = $this->getLocationCode($location);
            if (!$locationCode) {
                return back()->with('error', 'Location not found. Please try another name.');
            }

            $languageCode = $this->getLanguageCode($language);
            if (!$languageCode) {
                return back()->with('error', 'Language not found. Please try another name.');
            }

            $rank = $this->performSearch($keyword, $domain, $locationCode, $languageCode);
            
            if ($rank === null) {
                return back()->with('error', 'Website not found in search results.');
            }

            return back()->with('success', "Position of {$domain} for keyword '{$keyword}': {$rank}");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error executing request: ' . $e->getMessage());
        }
    }
    



    private function getLocationCode($locationName)
    {
        $response = Http::withBasicAuth(
            config('services.dataforseo.login'),
            config('services.dataforseo.password')
        )->get('https://api.dataforseo.com/v3/serp/google/locations');

        if ($response->status() === 401) {
            throw new \Exception('Authentication failed. Please check your DataForSEO API credentials in .env file.');
        }

        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['tasks'][0]['result'])) {
                foreach ($data['tasks'][0]['result'] as $location) {
                    if (stripos($location['location_name'], $locationName) !== false ||
                        stripos($locationName, $location['location_name']) !== false ||
                        strtolower($location['location_name']) === strtolower($locationName)) {
                        return $location['location_code'];
                    }
                }
                
                foreach ($data['tasks'][0]['result'] as $location) {
                    if (stripos($location['country_iso_code'], 'UA') !== false && 
                        stripos($location['location_name'], 'Ukraine') !== false) {
                        return $location['location_code'];
                    }
                }
            }
        }

        return null;
    }

    private function getLanguageCode($languageName)
    {
        $response = Http::withBasicAuth(
            config('services.dataforseo.login'),
            config('services.dataforseo.password')
        )->get('https://api.dataforseo.com/v3/serp/google/languages');

        if ($response->status() === 401) {
            throw new \Exception('Authentication failed. Please check your DataForSEO API credentials in .env file.');
        }

        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['tasks'][0]['result'])) {
                $languageMap = [
                    'українська' => 'uk',
                    'ukrainian' => 'uk',
                    'англійська' => 'en',
                    'english' => 'en',
                    'російська' => 'ru',
                    'russian' => 'ru'
                ];
                
                $searchLang = strtolower($languageName);
                if (isset($languageMap[$searchLang])) {
                    $targetCode = $languageMap[$searchLang];
                    foreach ($data['tasks'][0]['result'] as $language) {
                        if ($language['language_code'] === $targetCode) {
                            return $language['language_code'];
                        }
                    }
                }
                
                foreach ($data['tasks'][0]['result'] as $language) {
                    if (stripos($language['language_name'], $languageName) !== false ||
                        stripos($languageName, $language['language_name']) !== false ||
                        strtolower($language['language_name']) === strtolower($languageName)) {
                        return $language['language_code'];
                    }
                }
            }
        }

        return null;
    }

    private function performSearch($keyword, $domain, $locationCode, $languageCode)
    {
        $postData = [
            [
                'keyword' => $keyword,
                'location_code' => $locationCode,
                'language_code' => $languageCode,
                'device' => 'desktop',
                'os' => 'windows'
            ]
        ];

        $response = Http::withBasicAuth(
            config('services.dataforseo.login'),
            config('services.dataforseo.password')
        )->post('https://api.dataforseo.com/v3/serp/google/organic/live/advanced', $postData);

        if ($response->status() === 401) {
            throw new \Exception('Authentication failed. Please check your DataForSEO API credentials in .env file.');
        }

        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['tasks'][0]['result'][0]['items'])) {
                $items = $data['tasks'][0]['result'][0]['items'];
                
                $cleanDomain = preg_replace('/^(https?:\/\/)?(www\.)?/', '', $domain);
                $cleanDomain = rtrim($cleanDomain, '/');
                
                foreach ($items as $index => $item) {
                    if (isset($item['domain']) && (
                        stripos($item['domain'], $cleanDomain) !== false ||
                        stripos($cleanDomain, $item['domain']) !== false
                    )) {
                        return $index + 1;
                    }
                    
                    if (isset($item['url']) && (
                        stripos($item['url'], $cleanDomain) !== false ||
                        stripos($cleanDomain, parse_url($item['url'], PHP_URL_HOST)) !== false
                    )) {
                        return $index + 1;
                    }
                }
            }
        }

        return null;
    }
}
