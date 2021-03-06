<?php

/**
 *    Copyright 2018 The Lost Island : Battle Royale.
 *
 *    This file is part of TLIBR Web. TLIBR Web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of The Lost Island : Battle Royale.
 *
 *    TLIBR Web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License version 3
 *    as published by the Free Software Foundation.
 *
 *    TLIBR Web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with TLIBR Web.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|unique:team',
            'description' => 'required',
        ]);

        if (Team::count() === 0) {
            $sort_order = Team::count();
        } else {
            $sort_order = Team::orderBy('sort_order', 'desc')->firstOrFail()->sort_order + 1;
        }

        Team::create([
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'sort_order'  => $sort_order,
        ]);

        return redirect(route('dashboard.team'));
    }

    public function update_sortorder(Request $request)
    {
        for ($i = 0; $i < count(explode(',', $request->input('sort_order'))); $i++) {
            Team::where('id', explode(',', $request->input('sort_order'))[$i])
                ->update(['sort_order' => $i]);
        }

        return redirect(route('dashboard.team'));
    }

    public function destroy($id)
    {
        Team::where('id', $id)
            ->firstOrFail()
            ->delete();

        return redirect(route('dashboard.team'));
    }
}
