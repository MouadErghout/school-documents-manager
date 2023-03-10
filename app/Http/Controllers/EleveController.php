<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Module;
use App\Models\Moyenne;
use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules;


class EleveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('Eleves.index',['Eleves'=>$list = Eleve::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('eleves.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'code' => ['required', 'string', 'max:255', 'unique:'.Eleve::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->nom.$request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        event(new Registered($user));
        $E = new Eleve();
        $E->user_id=User::where('Name','=',$request->nom.$request->prenom)->first()->id;
        $E->code = $request->code;
        $E->nom = $request->nom;
        $E->prenom = $request->prenom;
        $E->filiere_code = $request->filiere;
        $E->niveau = $request->niveau;
        $E->save();
        return Redirect('Eleve');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eleve  $eleve
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */

    public function show(int $id)
    {
        $E = Eleve::find($id);
        return view('Eleves.show',['Eleve'=>$E,
            'User'=>$E->User,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eleve  $eleve
     * @return \Illuminate\Http\Response
     */
    public function edit(Eleve $eleve)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eleve  $eleve
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Eleve $eleve)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eleve  $eleve
     * @return \Illuminate\Http\Response
     */
    public function destroy(Eleve $eleve)
    {
        //
    }

    public function  releve($id)
    {
        $E=Eleve::find($id);
        $path = public_path('Releves de notes/' .$E->code.'.pdf');
        // header
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $E->code.'.pdf' . '"'
        ];
        return response()->file($path, $header);
    }

    public function  carte($id)
    {
        $E=Eleve::find($id);
        $path = public_path('Cartes des etudiants/' .$E->code.'.pdf');
        // header
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $E->code.'.pdf' . '"'
        ];
        return response()->file($path, $header);
    }

    public function storeImage(Request $request,$id)
    {
        $E=Eleve::find($id);

        if($request->file('image')){
            $file= $request->file('image');
            $filename= $file->getClientOriginalName();
            $file-> move(public_path('Cartes des etudiants/images'), $filename);
            $E->photo= $filename;
        }
        $E->save();
        return Redirect('/dashboard');
    }

    public function  emploi($id)
    {
        $E=Eleve::find($id);
        $path = public_path('Emplois du temps/' .$E->niveau.'.pdf');
        // header
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $E->code.'.pdf' . '"'
        ];
        return response()->file($path, $header);
    }

    public function  attestation($id)
    {
        $E=Eleve::find($id);
        $path = public_path('Attestations de scolarite/' .$E->code.'.pdf');
        // header
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $E->code.'.pdf' . '"'
        ];
        return response()->file($path, $header);
    }
}
