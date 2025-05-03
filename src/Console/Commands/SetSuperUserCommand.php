<?php

namespace NgarakDev\PermissionsUiWrapper\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Role;

class SetSuperUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions-ui:super-user 
                            {userId? : The ID of the user to set as super user} 
                            {--create : Create a new user as super user}
                            {--email= : Email for the new user (only with --create)}
                            {--name= : Name for the new user (only with --create)}
                            {--password= : Password for the new user (only with --create)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set a user as super user for managing permissions and roles';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('create')) {
            return $this->createSuperUser();
        }

        return $this->setExistingUserAsSuperUser();
    }

    /**
     * Set an existing user as super user.
     */
    protected function setExistingUserAsSuperUser()
    {
        $userId = $this->argument('userId');

        if (!$userId) {
            $userId = $this->ask('Enter the ID of the user to set as super user');
        }

        // Get the user model
        $userModel = $this->getUserModel();
        $user = $userModel::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return 1;
        }

        // Check if the user model uses the HasRoles trait
        if (!method_exists($user, 'assignRole')) {
            $this->error("The user model doesn't use the HasRoles trait from Spatie Permission package.");
            return 1;
        }

        // Get the super user role name from config
        $roleName = Config::get('permissions-ui.permissions_manager_role', 'Super Admin');

        // Check if the role exists, create it if not
        $role = $this->ensureRoleExists($roleName);

        // Assign the role to the user
        $user->assignRole($role);

        $this->info("User with ID {$userId} has been set as '{$roleName}'.");
        $this->info("They now have full access to manage all permissions and roles.");

        return 0;
    }

    /**
     * Create a new user as super user.
     */
    protected function createSuperUser()
    {
        // Get the user model
        $userModel = $this->getUserModel();

        // Get user details
        $name = $this->option('name') ?: $this->ask('Enter the name for the new super user');
        $email = $this->option('email') ?: $this->ask('Enter the email for the new super user');
        $password = $this->option('password') ?: $this->secret('Enter the password for the new super user');

        // Validate inputs
        if (empty($name) || empty($email) || empty($password)) {
            $this->error('Name, email, and password are required.');
            return 1;
        }

        // Check if user already exists
        if ($userModel::where('email', $email)->exists()) {
            $this->error("A user with email {$email} already exists.");

            if ($this->confirm('Do you want to find and use this existing user?')) {
                $user = $userModel::where('email', $email)->first();
                $this->info("Found user with ID {$user->id}.");
                $this->arguments()['userId'] = $user->id;
                return $this->setExistingUserAsSuperUser();
            }

            return 1;
        }

        // Create new user
        $user = $userModel::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        // Check if user was created successfully
        if (!$user) {
            $this->error('Failed to create user.');
            return 1;
        }

        // Get the super user role name from config
        $roleName = Config::get('permissions-ui.permissions_manager_role', 'Super Admin');

        // Check if the role exists, create it if not
        $role = $this->ensureRoleExists($roleName);

        // Assign the role to the user
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($role);
            $this->info("New super user '{$name}' created with ID {$user->id}.");
            $this->info("They have been assigned the '{$roleName}' role with full access to manage permissions and roles.");
        } else {
            $this->error("The user model doesn't use the HasRoles trait. Role assignment failed.");
            $this->info("User was created, but you'll need to manually assign roles.");
        }

        return 0;
    }

    /**
     * Get the User model class.
     */
    protected function getUserModel()
    {
        return Config::get('auth.providers.users.model', 'App\\Models\\User');
    }

    /**
     * Ensure the super user role exists.
     */
    protected function ensureRoleExists($roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->info("Creating '{$roleName}' role...");
            $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);

            // You might want to assign all permissions to this role
            if ($this->confirm("Do you want to assign all existing permissions to the '{$roleName}' role?")) {
                $permissions = \Spatie\Permission\Models\Permission::all();
                $role->syncPermissions($permissions);
                $this->info("All permissions assigned to '{$roleName}' role.");
            }
        }

        return $role;
    }
}
