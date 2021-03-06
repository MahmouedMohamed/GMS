<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Needy;
use App\Models\OfflineTransaction;
use App\Models\OnlineTransaction;
use App\Models\Profile;
use App\Models\CaseType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

use App\Models\BanTypes;
use Illuminate\Support\Str;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->content = array();
    }
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            // $loginBan = $user->bans()->where('active', '=', 1)->where('tag', '=', BanTypes::Login)->get()->first();
            // if ($loginBan) {
            //     return $this->sendForbidden('Sorry, but is seems you are banned from login until ' . ($loginBan['end_at'] ?? 'infinite period of time.'));
            // }
            $tokenDetails = $user->createAccessToken();
            $this->content['token'] =
                $tokenDetails['accessToken'];
            $this->content['expiryDate'] =
                $tokenDetails['expiryDate'];

            $this->content['user'] = Auth::user();
            // $profile = Profile::findOrFail(Auth::user()->profile);
            // $this->content['profile'] = $profile;
            return $this->sendResponse($this->content, 'Data Retrieved Successfully');
        } else {
            return $this->sendError('The email or password is incorrect.');
        }
    }

    public function details()
    {
        return response()->json(['user' => Auth::user()]);
    }

    public function register(Request $request)
    {
        $data = request()->all();
        $validated = $this->validateUser($request);
        if ($validated->fails()) {
            return $this->sendError('Invalid data', $validated->messages(), 400);
        }
        // $profile = Profile::create([]);
        // $image = $request['image'];
        // if ($image != null) {
        //     $imagePath = $image->store('users', 'public');
            // $profile->image = "/storage/" . $imagePath;
            // $profile->save();
        // }
        User::create([
            'id' => Str::uuid(),
            'name' => request('name'),
            'user_name' => request('user_name'),
            'email' => request('email'),
            'gender' => request('gender'),
            'password' => Hash::make(request('password')),
            'phone_number' => request('phone_number'),
            'address' => request('address'),
            'nationality' => request('nationality'),
            // 'profile' => $profile->id
        ]);
        return $this->sendResponse('', 'User Created Successfully');
    }
    public function validateUser(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'gender' => 'required|in:male,female',
            //|regex:^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$
            'phone_number' => 'required',
            'address' => 'string|max:1024',
            'image' => 'image',
            'nationality' => 'required|string'
        ], [
            'required' => 'This field is required',
            'min' => 'Invalid size, min size is :min',
            'max' => 'Invalid size, max size is :max',
            'integer' => 'Invalid type, only numbers are supported',
            'in' => 'Invalid type, support values are :values',
            'image' => 'Invalid type, only images are accepted',
            'mimes' => 'Invalid type, supported types are :values',
            'numeric' => 'Invalid type, only numbers are supported',
        ]);
    }
    public function getAhedAchievementRecords($id)
    {
        $user = User::find($id);

        //ToDo: Optimize Queries
        if ($user) {
            ///Get Number of needies that user helped
            $neediesApprovedForUser = Needy::where('createdBy', '=', $user->id)->where('approved', '=', '1')->get()->pluck('id')->unique()->toArray();
            $offlineDonationsForUser = OfflineTransaction::where('giver', '=', $user->id)->where('collected', '=', '1')->get();
            $onlineDonationsForUser = OnlineTransaction::where('giver', '=', $user->id)->get();

            $neediesDonatedOfflineFor =
                $offlineDonationsForUser->pluck('needy')->unique()->toArray();
            $neediesDonatedOnlineFor =
                $onlineDonationsForUser->pluck('needy')->unique()->toArray();
            $neediesHelped = collect(array_merge($neediesApprovedForUser, $neediesDonatedOfflineFor, $neediesDonatedOnlineFor));
            $this->content['NumberOfNeediesUserHelped'] = $neediesHelped->unique()->count();

            ///Get Value of all transactions
            $valueOfOfflineDonation = $offlineDonationsForUser
                ->pluck('amount')->toArray();
            $valueOfOnlineDonation = $onlineDonationsForUser
                ->pluck('amount')->toArray();
            $valueOfDontaion = collect(array_merge($valueOfOfflineDonation, $valueOfOnlineDonation));
            $this->content['ValueOfDonation'] = $valueOfDontaion->sum();
        }

        $activeNeedies = Needy::where('approved', '=', '1')->get();

        ///All Needies satisfied
        $neediesSatisfied = $activeNeedies->where('satisfied', '=', '1')->pluck('id')->unique()->count();
        $this->content['NeediesSatisfied'] = $neediesSatisfied;

        $caseType = new CaseType();
        ///All Needies safisfied with ?????????? ???????? ??????????
        $neediesFoundTheirNewHome = $activeNeedies->where('satisfied', '=', '1')->where('type', '=', $caseType->types[0])->pluck('id')->unique()->count();
        $this->content['NeediesFoundTheirNewHome'] = $neediesFoundTheirNewHome;
        ///All Needies safisfied with ?????????? ?????????? ??????????????
        $neediesUpgradedTheirStandardOfLiving = $activeNeedies->where('satisfied', '=', '1')->where('type', '=', $caseType->types[1])->pluck('id')->unique()->count();
        $this->content['NeediesUpgradedTheirStandardOfLiving'] = $neediesUpgradedTheirStandardOfLiving;
        ///All Needies safisfied with ?????????? ??????????
        $neediesHelpedToPrepareForPride = $activeNeedies->where('satisfied', '=', '1')->where('type', '=', $caseType->types[2])->pluck('id')->unique()->count();
        $this->content['NeediesHelpedToPrepareForPride'] = $neediesHelpedToPrepareForPride;
        ///All Needies safisfied with ????????
        $neediesHelpedToPayDept = $activeNeedies->where('satisfied', '=', '1')->where('type', '=', $caseType->types[3])->pluck('id')->unique()->count();
        $this->content['NeediesHelpedToPayDept'] = $neediesHelpedToPayDept;
        ///All Needies safisfied with ????????
        $neediesHelpedToCure = $activeNeedies->where('satisfied', '=', '1')->where('type', '=', $caseType->types[4])->pluck('id')->unique()->count();
        $this->content['NeediesHelpedToCure'] = $neediesHelpedToCure;

        ///neediesNotSatisfied
        $neediesNotSatisfied = Needy::where('satisfied', '=', '0')->get()->pluck('id')->unique()->count();
        $this->content['NeediesNotSatisfied'] = $neediesNotSatisfied;
        return $this->sendResponse($this->content, 'Achievement Records Returned Successfully');
    }

    public function updateProfilePicture(Request $request, $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return $this->sendError('???????????????? ?????? ??????????'); ///Case Not Found
        }
        if ($request['userId'] != $id)
            return $this->sendForbidden('?????? ???? ???????? ???????????? ?????????? ?????? ?????????? ????????????');  ///You aren\'t authorized to delete this transaction.

        $validated = $this->validateImage($request);
        if ($validated->fails())
            return $this->sendError('Invalid data', $validated->messages(), 400);

        $profile = Profile::find($user->profile);
        if ($profile->image == null) {
            $imagePath = $request['image']->store('users', 'public');
            $profile->image = "/storage/" . $imagePath;
            $profile->save();
        } else {
            $imagePath = $request['image']->storeAs('public/users', last(explode('/', $profile->image)));
        }
        return $this->sendResponse($profile->image, '???? ?????????? ???????????? ??????????');    ///Image Updated Successfully!

    }

    public function updateCoverPicture(Request $request, $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return $this->sendError('???????????????? ?????? ??????????'); ///Case Not Found
        }
        if ($request['userId'] != $id)
            return $this->sendForbidden('?????? ???? ???????? ???????????? ?????????? ?????? ?????????? ????????????');  ///You aren\'t authorized to delete this transaction.

        $validated = $this->validateImage($request);
        if ($validated->fails())
            return $this->sendError('Invalid data', $validated->messages(), 400);

        $profile = Profile::find($user->profile);
        if ($profile->cover == null) {
            $imagePath = $request['image']->store('users', 'public');
            $profile->cover = "/storage/" . $imagePath;
            $profile->save();
        } else {
            $imagePath = $request['image']->storeAs('public/users', last(explode('/', $profile->cover)));
        }
        return $this->sendResponse($profile->cover, '???? ?????????? ???????????? ??????????');    ///Image Updated Successfully!

    }
    public function updateinformation(Request $request, $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return $this->sendError('???????????????? ?????? ??????????'); ///Case Not Found
        }
        if ($request['userId'] != $id)
            return $this->sendForbidden('?????? ???? ???????? ???????????? ?????????? ?????? ?????????? ????????????');  ///You aren\'t authorized to delete this transaction.

        $profile = Profile::find($user->profile);
        $profile->bio = $request['bio'];
        $user->phone_number = $request['phoneNumber'];
        $user->address = $request['address'];
        $user->nationality = $request['nationality'];
        $profile->save();
        $user->save();
        return $this->sendResponse([], '???? ?????????? ?????????????? ??????????');    ///Image Updated Successfully!

    }
    public function validateImage(Request $request)
    {
        $rules = [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        return Validator::make($request->all(), $rules, [
            'image' => '???????? ???????????? ???????? ???????? ?????????? ??????',
        ]);
    }
}
