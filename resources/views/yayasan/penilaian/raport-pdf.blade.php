<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Raport - {{ $student->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 24px; margin: 0; }
        .header h2 { font-size: 18px; margin: 5px 0; color: #666; }
        .student-info { margin-bottom: 20px; }
        .student-info table { width: 100%; }
        .student-info td { padding: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .average { font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RAPORT SISWA</h1>
        <h2>{{ $academicYear?->name ?? 'Tahun Ajaran Aktif' }}</h2>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td width="150">Nama Siswa</td>
                <td><strong>{{ $student->name }}</strong></td>
            </tr>
            <tr>
                <td>NIS</td>
                <td>{{ $student->nis }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>{{ $student->classRoom?->name }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="vertical-align: middle;">Mata Pelajaran</th>
                <th colspan="4" class="text-center">Komponen Nilai</th>
                <th rowspan="2" style="vertical-align: middle;">Rata-rata</th>
            </tr>
            <tr>
                <th>UH</th>
                <th>Tugas</th>
                <th>UTS</th>
                <th>UAS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $subjectId => $subjectGrades)
            <tr>
                <td>{{ $subjectGrades->first()?->subject?->name }}</td>
                <td>{{ isset($averages[$subjectId]['by_type']['daily']) ? $averages[$subjectId]['by_type']['daily'] : '-' }}</td>
                <td>{{ isset($averages[$subjectId]['by_type']['assignment']) ? $averages[$subjectId]['by_type']['assignment'] : '-' }}</td>
                <td>{{ isset($averages[$subjectId]['by_type']['midterm']) ? $averages[$subjectId]['by_type']['midterm'] : '-' }}</td>
                <td>{{ isset($averages[$subjectId]['by_type']['final']) ? $averages[$subjectId]['by_type']['final'] : '-' }}</td>
                <td class="average">{{ $averages[$subjectId]['avg'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y') }}</p>
    </div>
</body>
</html>
