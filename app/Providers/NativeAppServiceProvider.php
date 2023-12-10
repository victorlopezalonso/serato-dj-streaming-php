<?php

namespace App\Providers;

use Native\Laravel\Dialog;
use Native\Laravel\Facades\MenuBar;
use Native\Laravel\Facades\Notification;
use Native\Laravel\Facades\Window;
use Native\Laravel\Contracts\ProvidesPhpIni;
use Native\Laravel\Menu\Menu;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    public const TITLE = 'Serato DJ Streaming';
    public const GIT_URL = 'https://github.com/victorlopezalonso/serato-dj-streaming-php';
    public const WIDTH = 1240;
    public const HEIGHT = 9999;
    public const POSITION_X = 9999;
    public const POSITION_Y = 0;
    public const APP_ICON = 'storage/images/menuBarIcon.png';
    public const SHOW_DOCK_ICON = true;
    public const SHOW_CONTEXT_MENU_ONLY = false;

    public function createMenu(): void
    {
        Menu::new()

            ->appMenu()
            ->editMenu('Edit')
            ->submenu(
                'View',
                Menu::new()
                ->toggleFullscreen()
                ->separator()
                ->toggleDevTools()
            )
            ->submenu(
                'About',
                Menu::new()
                ->link(self::GIT_URL, 'Github Repository')
            )
            ->register();
    }

    public function createMenuBar(): void
    {
        MenuBar::create()
            ->route('tray')
            ->icon(public_path(self::APP_ICON))
            ->onlyShowContextMenu(self::SHOW_CONTEXT_MENU_ONLY)
            ->showDockIcon(self::SHOW_DOCK_ICON)
            ->height(274)
            ->withContextMenu(
                Menu::new()
                    ->label(self::TITLE)
                    ->checkbox('Show Notifications', false)
                    ->separator()
                    ->link(self::GIT_URL, 'Learn more…')
                    ->separator()
                    ->quit()
            );
    }

    public function showNotification(): void
    {
        Notification::title('Notification Title')
            ->message('Notification Message')
            ->show();
    }

    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        //        $this->createMenu();
        //        $this->createMenuBar();
        //
        //        Window::open()
        //            ->route('home')
        //            ->title(self::TITLE)
        //            ->width(self::WIDTH)
        //            ->height(self::HEIGHT)
        //            ->position(self::POSITION_X, self::POSITION_Y)
        //            ->rememberState();

        //        $this->showNotification();
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}
