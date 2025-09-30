<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $data = User::latest()->paginate(5);
        return view('users.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    public function editIndex(Request $request)
    {
        $data = User::latest()->paginate(5);

        return view('users.edit-index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    public function create(): View
    {
        $roles = Role::pluck('name', 'id')->all();
        $permissions = Permission::all(); // Get all permissions for user-specific assignment

        $groupedPermissions = $permissions->groupBy(function ($item) {
            $parts = explode('-', $item->name);
            $group = Str::plural(end($parts));
            return ucfirst($group);
        });

        $lastUser = User::latest('id')->first();
        $nextEmployeeNumber = $lastUser ? $lastUser->id + 1 : 1;

        return view('users.create', compact('roles', 'permissions', 'groupedPermissions', 'nextEmployeeNumber'));
    }

    public function store(Request $request)
    {
        // -------------------- Validation --------------------
        $request->validate([
            // Basic info
            'username' => 'required|string|max:255',
            'birthdate' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            ],
            'contact_number_1' => 'required|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
            'contact_number_2' => 'nullable|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
            'joining_date' => 'required|date',
            'user_photo' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'user_photo_id' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'user_address_proof' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'employee_gender' => 'required|in:1,2',

            // ============ INSURANCE ============
            'insurance' => 'nullable|in:1,2',
            'insurance_name' => 'required_if:insurance,2|nullable|string|max:255',
            'insurance_policy_copy' => 'required_if:insurance,2|file|mimes:pdf,jpg,png,jpeg',
            'insurance_issue_date' => 'required_if:insurance,2|nullable|date',
            'insurance_valid_date' => 'required_if:insurance,2|nullable|date',

            // ============ NOMINEE ============
            'nominee_name' => 'required_if:insurance,2|nullable|string|max:255',
            'nominee_mobile_number' => 'required_if:insurance,2|nullable|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
            'nominee_photo_id' => 'required_if:insurance,2|nullable|file|mimes:jpg,png,jpeg,pdf',
            'nominee_address_proof' => 'required_if:insurance,2|nullable|file|mimes:jpg,png,jpeg,pdf',
            'nominee_gender' => 'required_if:insurance,2|nullable|in:1,2',
            'nominee_birthdate' => [
                'required_if:insurance,2',
                'nullable',
                'date',
                'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            ],

            // ============ USER TYPE / SALARY ============
            'user_type' => 'required|exists:roles,id',
            'department' => 'required|string|max:255',
            'salary' => 'required|numeric',
            'licence' => 'nullable|file|mimes:pdf,jpg,png,jpeg',

            // ============ BANK ============
            'bank' => 'required|in:1,2',
            'bank_proof' => 'required_if:bank,2|file|mimes:pdf,jpg,png,jpeg',

            // ============ COURT ============
            'court' => 'required|in:1,2',
            'court_case_files' => 'required_if:court,2|array',
            'court_case_files.*' => 'file|mimes:pdf,jpg,png,jpeg',
            'court_case_close_file.*' => 'nullable|file|mimes:pdf,jpg,png,jpeg',

            // ============ NOTE ============
            'note' => 'nullable|string|max:1000',

            // Permissions
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',

            // Email
            'email' => 'nullable|email|unique:users,email',
        ], [
            // Custom messages...
            'username.required' => 'Employee name is required.',
            'birthdate.required' => 'Please provide a valid birthdate.',
            'contact_number_1.required' => 'Primary contact number is required.',
            'contact_number_1.regex' => 'Contact number format is invalid.',
            'contact_number_2.regex' => 'Alternate contact number format is invalid.',
            'user_photo.required' => 'Employee image is required.',
            'user_photo.image' => 'Employee image must be an image file.',
            'user_photo_id.required' => 'Employee photo ID is required.',
            'user_address_proof.required' => 'Employee Address proof is required.',
            'insurance_name.required_if' => 'Insurance name is required when insurance is selected.',
            'insurance_policy_copy.required_if' => 'Upload insurance policy copy if insurance is selected.',
            'insurance_policy_copy.mimes' => 'Insurance policy copy must be a file of type: pdf, jpg, png.',
            'nominee_name.required_if' => 'Nominee name is required if insurance is selected.',
            'nominee_mobile_number.required_if' => 'Nominee mobile number is required.',
            'nominee_mobile_number.regex' => 'Nominee mobile number format is invalid.',
            'nominee_photo_id.required_if' => 'Nominee photo ID is required.',
            'nominee_address_proof.required_if' => 'Nominee address proof is required.',
            'nominee_gender.required_if' => 'Nominee gender is required.',
            'nominee_birthdate.required_if' => 'Nominee birthdate is required.',
            'user_type.required' => 'Please select a employee designation.',
            'user_type.exists' => 'Selected employee designation is invalid.',
            'department.required' => 'Department is required.',
            'salary.required' => 'Salary is required.',
            'salary.numeric' => 'Salary must be a valid number.',
            'licence.mimes' => 'License must be a file of type: pdf, jpg, png, jpeg.',
            'bank_proof.required_if' => 'Bank proof is required when bank option is selected.',
            'bank_proof.mimes' => 'Bank proof must be a file of type: pdf, jpg, png, jpeg.',
            'court_case_files.required_if' => 'Court case files are required when court is selected.',
            'court_case_files.*.mimes' => 'Each court file must be a valid file (pdf, jpg, png, jpeg).',
            'court_case_close_file.*.mimes' => 'Each court close file must be a valid file (pdf, jpg, png, jpeg).',
            'note.max' => 'Note cannot exceed 1000 characters.',
            'permissions.*.exists' => 'One or more selected permissions are invalid.',
            'email.unique' => 'This email address is already in use. Please provide a different email address.',
        ]);

        // -------------------- Prepare User Data --------------------
        $data = $request->except([
            'user_photo', 'user_photo_id', 'user_address_proof', 'insurance_policy_copy',
            'nominee_photo_id', 'nominee_address_proof', 'licence', 'bank_proof',
            'court_case_files', 'court_case_close_file'
        ]);

        $data['name'] = $request->input('username');

        if (empty($data['email'])) {
            $baseEmail = trim(str_replace(' ', '', strtolower($request->username))) . '@krishnaminerals.com';
            $email = $baseEmail;
            $counter = 1;

            while (User::where('email', $email)->exists()) {
                $email = trim(str_replace(' ', '', strtolower($request->username))) . $counter . '@krishnaminerals.com';
                $counter++;
            }

            $data['email'] = $email;
        }

        if (empty($data['password'])) {
            $plainPassword = \Str::random(10);
            $data['password'] = \Hash::make($plainPassword);
        }

        // -------------------- Create User --------------------
        $user = \App\Models\User::create($data);

        // Assign direct permissions
        if ($request->has('permissions')) {
            $permissionIds = $request->input('permissions');
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $user->syncPermissions($permissions);
        }

        // -------------------- Prepare User Folder Structure --------------------
        $userFolder = "users/user_{$user->id}";
        $subFolders = ['bank', 'nominee', 'insurance', 'court_case', 'court_case_close'];

        foreach ($subFolders as $sub) {
            if (!\Storage::disk('public')->exists("$userFolder/$sub")) {
                \Storage::disk('public')->makeDirectory("$userFolder/$sub");
            }
        }

        // -------------------- File Upload Handling --------------------
        $fileMap = [
            'user_photo' => '', 
            'user_photo_id' => '', 
            'user_address_proof' => '', 
            'insurance_policy_copy' => 'insurance',
            'nominee_photo_id' => 'nominee',
            'nominee_address_proof' => 'nominee',
            'licence' => '', 
            'bank_proof' => 'bank',
        ];

        $filePathsToUpdate = [];

        foreach ($fileMap as $file => $subFolder) {
            if ($request->hasFile($file)) {
                $filename = time() . '_' . uniqid() . '_' . $request->file($file)->getClientOriginalName();
                $path = $subFolder ? "$userFolder/$subFolder" : $userFolder;
                $request->file($file)->storeAs($path, $filename, 'public');

                // Store only the filename in DB
                $filePathsToUpdate[$file] = $filename;
            }
        }

        // -------------------- Handle Court Case Files --------------------
        $courtCases = [];
        $maxCases = max(
            count($request->file('court_case_files') ?? []),
            count($request->file('court_case_close_file') ?? [])
        );

        for ($i = 0; $i < $maxCases; $i++) {
            $caseFiles = [];
            $closeFiles = [];

            if ($request->hasFile('court_case_files') && isset($request->file('court_case_files')[$i])) {
                $files = $request->file('court_case_files')[$i];
                if (!is_array($files)) $files = [$files];

                foreach ($files as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->storeAs("$userFolder/court_case", $filename, 'public');

                    // Store only filename
                    $caseFiles[] = $filename;
                }
            }

            if ($request->hasFile('court_case_close_file') && isset($request->file('court_case_close_file')[$i])) {
                $files = $request->file('court_case_close_file')[$i];
                if (!is_array($files)) $files = [$files];

                foreach ($files as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->storeAs("$userFolder/court_case_close", $filename, 'public');

                    // Store only filename
                    $closeFiles[] = $filename;
                }
            }

            $courtCases[] = [
                'case_files' => $caseFiles,
                'case_close_files' => $closeFiles
            ];
        }

        $filePathsToUpdate['court_case_files'] = json_encode($courtCases);

        $user->update($filePathsToUpdate);


        // -------------------- Session for PDF --------------------
        session([
            'pdf_user_id' => $user->id,
            'pdf_plain_password' => $plainPassword,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Employee created successfully.')
            ->with('auto_download_pdf', true);
    }

    public function show($id): View
    {
        $user = User::findOrFail($id); // <-- throws 404 if user not found

        return view('users.show', compact('user'));
    }

    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'id')->all();

        $permissions = Permission::all(); // Get all permissions for user-specific assignment
            
        $groupedPermissions = $permissions->groupBy(function ($item) {
            $parts = explode('-', $item->name);
            $group = Str::plural(end($parts)); 
            return ucfirst($group);
        });

        $userRole = $user->roles->pluck('id')->first();
        $userPermissions = $user->permissions->pluck('id')->toArray(); // Get user-specific permissions

        return view('users.edit', compact('user', 'roles', 'permissions','groupedPermissions', 'userRole', 'userPermissions'));
    }

    public function update(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        // -------------------- Build dynamic validation rules --------------------
        $rules = [
            'username' => 'required|string|max:255',
            'birthdate' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'contact_number_1' => 'nullable|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
            'contact_number_2' => 'nullable|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
            'joining_date' => 'required|date',
            'user_photo' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'user_photo_id' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'user_address_proof' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'employee_gender' => 'required|in:1,2',
            'insurance' => 'required|in:1,2',
            'user_type' => 'required|exists:roles,id',
            'department' => 'required|string|max:255',
            'salary' => 'nullable|numeric',
            'licence' => 'nullable|file|mimes:pdf,jpg,png,jpeg',
            'bank' => 'required|in:1,2',
            'court' => 'required|in:1,2',
            'note' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'email' => 'nullable|email|unique:users,email,' . $id,
        ];

        if ($request->input('bank') == 2 && empty($user->bank_proof)) {
            $rules['bank_proof'] = 'required|file|mimes:pdf,jpg,png,jpeg';
        }

        if ($request->input('insurance') == 2) {
            $rules['insurance_name'] = 'required|string|max:255';
            $rules['insurance_policy_copy'] = empty($user->insurance_policy_copy) && !$request->hasFile('insurance_policy_copy') 
                ? 'required|file|mimes:pdf,jpg,png,jpeg'
                : 'nullable|file|mimes:pdf,jpg,png,jpeg';
            $rules['insurance_issue_date'] = 'required|date';
            $rules['insurance_valid_date'] = 'required|date';

            $rules['nominee_name'] = 'required|string|max:255';
            $rules['nominee_mobile_number'] = 'required|string|min:10|max:10|regex:/^[0-9+\-\s]+$/';
            $rules['nominee_photo_id'] = empty($user->nominee_photo_id) && !$request->hasFile('nominee_photo_id') 
                ? 'required|file|mimes:jpg,png,jpeg,pdf' 
                : 'nullable|file|mimes:jpg,png,jpeg,pdf';
            $rules['nominee_address_proof'] = empty($user->nominee_address_proof) && !$request->hasFile('nominee_address_proof') 
                ? 'required|file|mimes:jpg,png,jpeg,pdf' 
                : 'nullable|file|mimes:jpg,png,jpeg,pdf';
            $rules['nominee_gender'] = 'required|in:1,2';
            $rules['nominee_birthdate'] = 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d');
        }

        $courtCases = json_decode($user->court_case_files ?? '[]', true);
        if ($request->input('court') == 2) {
            $hasExistingFiles = false;
            if (is_array($courtCases) && count($courtCases) > 0) {
                foreach ($courtCases as $case) {
                    if (!empty($case['case_files'])) {
                        $hasExistingFiles = true;
                        break;
                    }
                }
            }
            if (!$hasExistingFiles && !$request->hasFile('court_case_files')) {
                $rules['court_case_files'] = 'required|array';
                $rules['court_case_files.*.*'] = 'file|mimes:pdf,jpg,png,jpeg';
            } else {
                $rules['court_case_files.*.*'] = 'nullable|file|mimes:pdf,jpg,png,jpeg';
            }
            $rules['court_case_close_file.*.*'] = 'nullable|file|mimes:pdf,jpg,png,jpeg';
        }

        $validatedData = $request->validate($rules);

        // -------------------- Create user folder if needed --------------------
        $userFolder = "users/user_{$user->id}";
        $subFolders = ['bank', 'nominee', 'insurance', 'court_case', 'court_case_close'];
        foreach ($subFolders as $sub) {
            if (!\Storage::disk('public')->exists("$userFolder/$sub")) {
                \Storage::disk('public')->makeDirectory("$userFolder/$sub");
            }
        }

        // -------------------- Extract non-file data --------------------
        $data = $request->except([
            'user_photo', 'user_photo_id', 'user_address_proof', 'insurance_policy_copy',
            'nominee_photo_id', 'nominee_address_proof', 'licence', 'bank_proof',
            'court_case_files', 'court_case_close_file'
        ]);
        $data['name'] = $request->username;
        if (empty($data['email'])) {
            $data['email'] = trim(str_replace(' ', '', strtolower($request->username))) . '@krishnaminerals.com';
        }

        // -------------------- Upload Main Files (store only filename) --------------------
        $mainFolderFiles = ['user_photo', 'user_photo_id'];
        foreach ($mainFolderFiles as $file) {
            if ($request->hasFile($file)) {
                if (!empty($user->$file) && \Storage::disk('public')->exists("$userFolder/$user->$file")) {
                    \Storage::disk('public')->delete("$userFolder/$user->$file");
                }
                $filename = time() . '_' . uniqid() . '_' . $request->file($file)->getClientOriginalName();
                $request->file($file)->storeAs($userFolder, $filename, 'public');
                $data[$file] = $filename;
            }
        }

        // -------------------- Upload Subfolder Files (store only filename) --------------------
        $fileMap = [
            'user_address_proof' => '',
            'insurance_policy_copy' => 'insurance',
            'nominee_photo_id' => 'nominee',
            'nominee_address_proof' => 'nominee',
            'licence' => '',
            'bank_proof' => 'bank',
        ];

        foreach ($fileMap as $file => $subFolder) {
            if ($request->hasFile($file)) {
                if (!empty($user->$file) && \Storage::disk('public')->exists("$userFolder/$subFolder/$user->$file")) {
                    \Storage::disk('public')->delete("$userFolder/$subFolder/$user->$file");
                }
                $filename = time() . '_' . uniqid() . '_' . $request->file($file)->getClientOriginalName();
                $path = $subFolder ? "$userFolder/$subFolder" : $userFolder;
                $request->file($file)->storeAs($path, $filename, 'public');
                $data[$file] = $filename;
            }
        }

        // -------------------- Handle Court Case Files (store only filenames) --------------------
        $courtCases = json_decode($user->court_case_files ?? '[]', true);
        if (!is_array($courtCases)) $courtCases = [];

        $newCourtFiles = $request->file('court_case_files') ?? [];
        $newCourtCloseFiles = $request->file('court_case_close_file') ?? [];

        function moveUploadedFiles($files, $folder, $userFolder) {
            $paths = [];
            if (!$files) return $paths;
            if (!is_array($files)) $files = [$files];
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $file->storeAs("$userFolder/$folder", $filename, 'public');
                    $paths[] = $filename;
                }
            }
            return $paths;
        }

        $allIndexes = array_unique(array_merge(
            array_keys($courtCases),
            array_keys($newCourtFiles),
            array_keys($newCourtCloseFiles)
        ));
        sort($allIndexes);

        foreach ($allIndexes as $index) {
            $caseFiles = moveUploadedFiles($newCourtFiles[$index] ?? [], 'court_case', $userFolder);
            $closeFiles = moveUploadedFiles($newCourtCloseFiles[$index] ?? [], 'court_case_close', $userFolder);

            if (isset($courtCases[$index])) {
                $courtCases[$index]['case_files'] = array_merge($courtCases[$index]['case_files'] ?? [], $caseFiles);
                $courtCases[$index]['case_close_files'] = array_merge($courtCases[$index]['case_close_files'] ?? [], $closeFiles);
            } elseif (!empty($caseFiles) || !empty($closeFiles)) {
                $courtCases[$index] = [
                    'case_files' => $caseFiles,
                    'case_close_files' => $closeFiles,
                ];
            }
        }
        $data['court_case_files'] = json_encode(array_values($courtCases));

        // -------------------- Sync Permissions --------------------
        $permissionIds = $request->input('permissions', []);
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        $user->syncPermissions($permissions);

        // -------------------- Update User --------------------
        $user->update($data);

        return redirect()->route('users.editIndex')->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);

        // -------------------- Delete user files --------------------
        $userFolder = "users/user_{$user->id}";

        if (\Storage::disk('public')->exists($userFolder)) {
            // Recursive delete
            \Storage::disk('public')->deleteDirectory($userFolder);
        }

        // -------------------- Delete user from database --------------------
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Employee deleted successfully.');
    }


    /**
     * Recursive folder delete
     */
    private function deleteFolder($folder)
    {
        if (!file_exists($folder)) return;

        $files = array_diff(scandir($folder), ['.', '..']);

        foreach ($files as $file) {
            $path = $folder . '/' . $file;
            if (is_dir($path)) {
                $this->deleteFolder($path);
            } else {
                @unlink($path); // @ to suppress errors if file doesn't exist
            }
        }

        @rmdir($folder); // Remove the folder itself
    }


    public function streamPdf(User $user)
    {
        // Get plain password from session, or fallback
        $plainPassword = session('pdf_plain_password', 'N/A');
    
        $pdfData = [
            'username' => $user->username,
            'email' => $user->email,
            'password' => $plainPassword,
            'department' => $user->department,
            'salary'=> $user->salary,
            'created_at' => $user->created_at->timezone('Asia/Kolkata')->format('d-m-Y H:i:s'),

        ];
    
        $pdf = \PDF::loadView('users.pdf', $pdfData);
    
        return $pdf->download('User_Credentials.pdf');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'old_password.required' => 'Old password is required.',
            'password.required' => 'New password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = Auth::user();
        
        // Check if the old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'The old password is incorrect.']);
        }

        // Update with new password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}

