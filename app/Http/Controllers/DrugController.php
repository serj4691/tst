<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\Substance;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drugs = Drug::all();
        $drugs_list = array();
        foreach ($drugs as $drug) {
            $item = array('item_id' => $drug->id, 'item_name' => $drug->name);
            array_push($drugs_list, $item);
        }
        //dd($drugs_list);
        return view('drugs.index', array('drug_item' => $drugs_list));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $choice = 0;
        $avalibleDrugs = array();
        $substances = Substance::select('*')->where('visible', 1)->get();
        $substances_list = array();
        foreach ($substances as $s) {
            $item = array('substance_id' => $s->id, 'substance_name' => $s->name);
            array_push($substances_list, $item);
        }
        if ($choice) {
            $drugs = Drug::with('substances')->get();
            foreach ($drugs as $drug) {
                $avalible = 1;
                foreach ($drug->substances()->get() as $d) {
                    if (!$d['visible']) {
                        //dd($d['visible'], $d['id']);
                        $avalible = 0;
                    }
                }
                if ($avalible) {
                    $avalibleDrugs[$drug['id']] = $drug['name'];
                }
            }
        }

        //dd($avalibleDrugs);
        return view('drugs.create', array('substance_items' => $substances_list), array('avalibleDrugs' => $avalibleDrugs));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $items = $request->all();
        //dd($items['substances']);
        flash('');
        if (!array_key_exists('substances', $items)) {
            flash('Не выбран ни один эдемент', 'alert-warning');
            return back();
        } else {
            $count = count($items['substances']);

            if ($count < 2) {
                //dd($items, $count);
                flash('не ленись, добавь веществ', 'alert-warning');
                return back();
            }
            if ($count > 4) {
                //dd($items, $count);
                flash('Слишком много выбрано веществ', 'alert-warning');
                return back();
            }
            $avalibleDrugs = array();
            $drugs = Drug::with('substances')->get();
            //dd($drugs, $items['substances']);
            $count = 0;
            foreach ($drugs as $drug) {
                $avalible = 1;
                $arr = array();
                foreach ($drug->substances()->get() as $d) {

                    if (!$d['visible']) {
                        //dd($d['visible'], $d['id']);
                        $avalible = 0;
                        break;
                    }
                    array_push($arr, $d['name']);
                }
                //dd($arr);
                if ($avalible) {
                    $ident = count($items['substances']);

                    $result = array_diff($items['substances'], $arr);
                    //dd($ident);
                    $avalibleDrugs[$count]['name'] = $drug['name'];
                    $avalibleDrugs[$count]['count'] = count($result);
                    $avalibleDrugs[$count]['ident'] = $ident - count($result);
                    //dd($avalibleDrugs);
                    $count++;
                }
            }
            $aDrugs = array();
            foreach ($avalibleDrugs as $a) {   // совпадения все
                if ($a['count'] == 0) {
                    array_push($aDrugs, $a);
                }
            }
            //dd($aDrugs);
            if (count($aDrugs) == 0) {
                foreach ($avalibleDrugs as $a) {
                    if ($a['count'] == 1 && $a['ident'] > 1) {
                        array_push($aDrugs, $a);
                    }
                }
                foreach ($avalibleDrugs as $a) {
                    if ($a['count'] == 2 && $a['ident'] > 1) {
                        array_push($aDrugs, $a);
                    }
                }
            }
            if (count($aDrugs) == 0) {
                flash('Совпадеий не найдено', 'alert-warning');
                return back();
            }
            return view('drugs.on_create', array('avalibleDrugs' => $aDrugs));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return view('drugs.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
