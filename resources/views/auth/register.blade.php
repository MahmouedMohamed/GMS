@extends('layouts.app')
@section('content')
    <x-guest-layout>
        <x-auth-card>
            <x-slot name="logo">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </x-slot>

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <label class="font-bold font-weight-bold">Main Information</label>
                <div class="mx-2 my-2">
                    <!-- Name -->
                    <div>
                        <x-label for="name" :value="__('Name')" />

                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                            autofocus />
                    </div>

                    <!-- User Name -->
                    <div>
                        <x-label for="user_name" :value="__('User Name')" />

                        <x-input id="user_name" class="block mt-1 w-full" type="text" name="user_name"
                            :value="old('user_name')" required />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-label for="email" :value="__('Email')" />

                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                            required />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-label for="password" :value="__('Password')" />

                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="new-password" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-label for="password_confirmation" :value="__('Confirm Password')" />

                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" required />
                    </div>
                </div>

                <label class="font-bold font-weight-bold">Additional Information</label>
                <div class="mx-2 my-2">
                    <!-- Gender -->
                    <div class="d-flex align-items-center row">
                        <div class="col-3">
                            <x-label for="gender" :value="__('Gender')" />
                        </div>
                        <div class="col-4">

                            <input type="radio" id="male" name="gender" value="male" required>
                            <label for="male">Male</label>
                        </div>

                        <div class="col-5">
                            <input type="radio" id="female" name="gender" value="female">
                            <label for="female">Female</label>
                        </div>

                    </div>
                    <!-- Phome Number -->
                    <div>
                        <x-label for="phone_number" :value="__('Phone Number')" />

                        <x-input id="phone_number" class="block mt-1 w-full" type="tel" name="phone_number"
                            :value="old('phone_number')" required />
                    </div>
                    <!-- Address -->
                    <div>
                        <x-label for="address" :value="__('Address')" />

                        <x-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')"
                            required />
                    </div>
                    <!-- Nationality -->
                    <div class="d-flex align-items-center row mt-3">
                        <div class="col-4">
                            <x-label for="nationality" :value="__('Nationality')" />
                        </div>
                        <div class="col-8">

                            <select id="nationality" name="nationality" class="form-control">
                                @include('auth.countries')
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-button class="ml-4">
                        {{ __('Register') }}
                    </x-button>
                </div>
            </form>
        </x-auth-card>
    </x-guest-layout>
@endsection
