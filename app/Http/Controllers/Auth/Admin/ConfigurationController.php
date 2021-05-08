<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ConfigService;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelSettings\SettingsRepositories\SettingsRepository;

class ConfigurationController extends Controller
{
    private ConfigService $configService;

    private SettingsRepository $settingsRepository;

    /**
     * ConfigurationController constructor.
     * @param ConfigService $configService
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(ConfigService $configService, SettingsRepository $settingsRepository)
    {
        $this->configService = $configService;
        $this->settingsRepository = $settingsRepository;
    }


    public function index()
    {

        $settings = Setting::all();
        return view('settings.index', [
            'settings' => $settings,
            'groups' => $this->configService->getGroups(),
        ]);
    }


    public function setItem()
    {
        return view('settings.genaral', [
            'groups' => $this->configService->getGroups(),
        ]);
    }

    public function create()
    {
        $configService = $this->configService;
        $group = request()->group;
        return view('settings.'.$group, [
           'groups' => $configService->getGroups(),
            'group' => $group,
            'names' => [
                'general' => $configService->getNames('general'),
                'pusher' => $configService->getNames('pusher'),
                'algolia' => $configService->getNames('algolia'),
                'weather' => $configService->getNames('weather'),
                'aws' => $configService->getNames('aws'),
            ]
        ]);
    }

    public function store()
    {
        $group = request()->group;

        foreach (request()->all() as $item => $value) {
            if (!$this->settingsRepository->checkIfPropertyExists($group, $item)) {
                continue ;
            }

            if ($value === null) {
                continue;
            }

            $this->settingsRepository->updatePropertyPayload($group, $item, $value);
        }

        return \Redirect::to('/settings/show/'.$group);
    }

    public function delete()
    {
        return true;
    }

    public function show()
    {

        $group = request()->group;

        $settings = Setting::query()
            ->where('group', $group)->get()
            ->map(function ($setting) {
                    return [
                        'name' => $setting->name,
                        'payload' => $setting->payload,
                    ];
            });

        return view('settings.all_settings', [
            'settings' => $settings,
            'group' => $group
        ]);
    }
}
