<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" id="registrationForm">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" class="block mt-1 w-full" required x-on:change="roleChange($event.target.value)">
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
                <!-- Add other role options if needed -->
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Additional Fields for Student -->
        <div id="studentFields" class="mt-4">
            <x-input-label for="roll" :value="__('Roll')" />
            <x-text-input id="roll" class="block mt-1 w-full" type="number" name="roll" :value="old('roll')" required />
            <x-input-error :messages="$errors->get('roll')" class="mt-2" />

            <x-input-label for="semester" :value="__('Semester')" />
            <select id="semester" name="semester" class="block mt-1 w-full" required>
                <option value="1">1st Semester</option>  
                <option value="2">2nd Semester</option>  
                <option value="3">3rd Semester</option>  
                <option value="4">4th Semester</option>  
                <option value="5">5th Semester</option>  
                <option value="6">6th Semester</option>  
                <option value="7">7th Semester</option>  
                <option value="8">8th Semester</option>  
            </select>
            <x-input-error :messages="$errors->get('semester')" class="mt-2" />

            <x-input-label for="session" :value="__('Session')" />
            <x-text-input id="session" class="block mt-1 w-full" type="text" name="session" :value="old('session')" required />
            <x-input-error :messages="$errors->get('session')" class="mt-2" />
        </div>

        <!-- Additional Fields for Teacher -->
        <div id="teacherFields" class="mt-4" style="display: none;">
            <x-input-label for="qualification" :value="__('Qualification')" />
            <x-text-input id="qualification" class="block mt-1 w-full" type="text" name="qualification" :value="old('qualification')" required />
            <x-input-error :messages="$errors->get('qualification')" class="mt-2" />
        </div>

        <x-input-label for="department" :value="__('Department')" />
        <select id="department" name="department" class="block mt-1 w-full" required>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('department')" class="mt-2" />

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            const roleSelect = $('#role');
            const studentFields = $('#studentFields');
            const teacherFields = $('#teacherFields');

            function toggleFields() {
                const selectedRole = roleSelect.val();

                if (selectedRole === 'student') {
                    studentFields.find('input, select').prop('disabled', false);
                    teacherFields.find('input, select').prop('disabled', true);
                    studentFields.show();
                    teacherFields.hide();
                } else if (selectedRole === 'teacher') {
                    studentFields.find('input, select').prop('disabled', true);
                    teacherFields.find('input, select').prop('disabled', false);
                    studentFields.hide();
                    teacherFields.show();
                } else {
                    studentFields.find('input, select').prop('disabled', true);
                    teacherFields.find('input, select').prop('disabled', true);
                    studentFields.hide();
                    teacherFields.hide();
                }
            }

            // Trigger the function on initial load and whenever the role selection changes
            toggleFields();
            roleSelect.on('change', toggleFields);

            // When the Register button is clicked, submit the form
            $('#registerButton').on('click', function() {
                $('#registrationForm').submit();
            });
        });
    </script>
</x-guest-layout>
