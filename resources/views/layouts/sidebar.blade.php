<div class="sidebar">
    <ul class="sidebar-menu">
        <li><a href={{ route('staffs.index')}}>社員一覧</a></li>
    </ul>
    <ul class="sidebar-menu">
        <li><a href={{ route('shiftTypes.edit')}}>シフトタイプ編集</a></li>
    </ul>
    <ul class="sidebar-menu">
        <li><a href={{ route('shiftSubmissions.display', ['id' => $id])}}>シフト提出</a></li>
    </ul>
    <ul class="sidebar-menu">
        <li><a href={{ route('shifts.display', ['id' => $id])}}>シフト作成</a></li>
    </ul>
</div>
