<x-filament-panels::page>
    @php
        $firstDate = \Carbon\CarbonImmutable::create($year, $month, 1);
        $dowShort  = ['Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb', 'Mg']; // 1=Mon..7=Sun
    @endphp

    <div class="shift-board">

        {{-- ====== Top controls ====== --}}
        <div class="shift-board__header">
            <div class="shift-board__nav">
                <button type="button" wire:click="previousMonth" class="shift-board__nav-btn" title="Previous month">‹</button>
                <div class="shift-board__month">{{ $month_name }} {{ $year }}</div>
                <button type="button" wire:click="nextMonth" class="shift-board__nav-btn" title="Next month">›</button>
            </div>

            <div class="shift-board__meta">
                <strong>{{ count($employees) }}</strong> employees &nbsp;•&nbsp;
                <strong>{{ $days_in_month }}</strong> days
            </div>

            <div class="shift-board__actions">
                {{ $this->uploadAction }}
                {{ $this->clearScheduleAction }}
            </div>
        </div>

        {{-- ====== Schedule grid ====== --}}
        <div class="shift-board__grid-wrap">
            <table class="shift-grid">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-name">Nama</th>
                        <th class="col-empid">Employee ID</th>
                        @for($d = 1; $d <= $days_in_month; $d++)
                            @php
                                $date     = $firstDate->setDay($d);
                                $dowIso   = (int) $date->dayOfWeekIso;
                                $isWknd   = $dowIso >= 6;
                                $holiday  = $holidays[$date->toDateString()] ?? null;
                                $isHol    = $holiday !== null;
                                $thClass  = trim(
                                    ($isWknd ? 'is-weekend ' : '') .
                                    ($isHol  ? 'is-holiday ' : '')
                                );
                            @endphp
                            <th class="cell {{ $thClass }}" title="{{ $date->toDateString() }}{{ $isHol ? ' • ' . ($holiday['name'] ?? '') : '' }}">
                                <div>{{ $d }}</div>
                                <div class="dow">{{ $dowShort[$dowIso - 1] ?? '' }}</div>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $i => $emp)
                        <tr>
                            <td class="col-no">{{ $i + 1 }}</td>
                            <td class="col-name">{{ $emp['name'] }}</td>
                            <td class="col-empid">{{ $emp['employee_id'] }}</td>
                            @for($d = 1; $d <= $days_in_month; $d++)
                                @php
                                    $cell = $grid[$emp['id']][$d] ?? null;
                                    $bg   = $cell['color']      ?? '#ffffff';
                                    $fg   = $cell['text_color'] ?? '#000000';
                                    $title = '';
                                    if ($cell) {
                                        $title = ($cell['name'] ?? '') .
                                            ($cell['start_time'] && $cell['end_time'] ? ' (' . $cell['start_time'] . '–' . $cell['end_time'] . ')' : '');
                                        if (!empty($cell['holiday_name'])) {
                                            $title .= ' • ' . $cell['holiday_name'];
                                        }
                                    }
                                @endphp
                                <td class="cell" style="background:{{ $bg }}; color:{{ $fg }};" title="{{ $title }}">
                                    <span class="code">{{ $cell['code'] ?? '' }}</span>
                                </td>
                            @endfor
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 3 + $days_in_month }}" class="shift-board__empty">
                                <p>No shift schedule uploaded for this period.</p>
                                <p class="hint">Click <strong>Upload Schedule</strong> above, download the template, fill in shift codes per day per employee, then re-upload.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ====== Legend ====== --}}
        <div class="shift-board__legend">
            <h3>Legend</h3>
            <div class="shift-board__legend-grid">
                @foreach($legend as $l)
                    <div class="legend-card">
                        <div class="legend-card__head" style="background:{{ $l['color'] }}; color:{{ $l['text_color'] }};">
                            <span>{{ $l['code'] }}</span>
                            <span style="font-weight:400; opacity:.7; font-size:11px;">{{ $l['is_off'] ? 'OFF' : '' }}</span>
                        </div>
                        <div class="legend-card__name">{{ $l['name'] }}</div>
                        <div class="legend-card__time">
                            @if($l['start_time'] && $l['end_time'])
                                {{ $l['start_time'] }} – {{ $l['end_time'] }}
                            @else
                                {{ $l['is_off'] ? 'Hari Libur' : '—' }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
