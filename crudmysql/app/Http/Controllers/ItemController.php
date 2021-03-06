<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use Maatwebsite\Excel\Facades\Excel;
use Input;
class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::latest()->paginate(10);
        //items -> is view name
        //compact items is $items variable
        return view('items',compact('items'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  //   public function import(Request $request)
  //   {
  //     if($request->file('imported-file'))
  //     {
  //               $path = $request->file('imported-file')->getRealPath();
  //               $data = Excel::load($path, function($reader) {
  //           })->get();

  //           if(!empty($data) && $data->count())
  //     {
  //       $data = $data->toArray();
  //       for($i=0;$i<count($data);$i++)
  //       {
  //         $dataImported[] = $data[$i];
  //       }
  //           }
  //     Item::insert($dataImported);
  //       }
  //       return back();
  // }
     public function import(Request $request)
    {
      if($request->file('imported-file'))
      {
                $path = $request->file('imported-file')->getRealPath();
                $data = Excel::load($path, function($reader)
          {
                })->get();

          if(!empty($data) && $data->count())
          {
            $i=0;
            foreach ($data->toArray() as $row)
            {

              if(!empty($row))
              {
                // echo '<pre>';
                // print_r($row);exit;
                $dataArray[] =
                [
                  'item_name' => $row[$i]['name'],
                  'item_code' => $row[$i]['code'],
                  'item_price' => $row[$i]['price'],
                  'item_qty' => $row[$i]['quantity'],
                  'item_tax' => $row[$i]['tax'],
                  'item_status' => $row[$i]['status'],
                  'created_at' => $row[$i]['created_at']
                ];
                $i++;
              }
          }
          if(!empty($dataArray))
          {
             Item::insert($dataArray);
             return back();
           }
         }
       }
    }

    /**
     * export a file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(){
      $items = Item::all();
      Excel::create('items', function($excel) use($items) {
          $excel->sheet('ExportFile', function($sheet) use($items) {
              $sheet->fromArray($items);
          });
      })->export('xls');

    }
    public function exportPDF()
    {
       $data = Item::get()->toArray();
       return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
        $excel->sheet('mySheet', function($sheet) use ($data)
        {
            $sheet->fromArray($data);
        });
       })->download("pdf");
    }
}
