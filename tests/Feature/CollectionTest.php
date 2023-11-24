<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Data\Person;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PDO;

use function PHPUnit\Framework\assertTrue;

class CollectionTest extends TestCase
{
    public function testCreateCollection (): void
    {
        $collect = collect([1, 2, 3]);

        $this->assertTrue($collect->contains(2));
        $this->assertEqualsCanonicalizing([1, 2, 3], $collect->all());
        $this->assertCount(3, $collect->all());
    }

    public function testForEach (): void
    {
        $collect = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $this->assertTrue($collect->contains(8));
        $this->assertEqualsCanonicalizing([9, 8, 7, 6, 5, 4, 3, 2, 1], $collect->all());
        $this->assertCount(9, $collect->all());

        $collect->each( function (int $item, int $key) {
            $this->assertEquals($key + 1, $item);
        });
    }

    public function testOperationCollection (): void
    {
        $collect = collect([1, 2, 3]);
        $collect->push(4);

        $this->assertTrue($collect->contains(3));
        $this->assertEqualsCanonicalizing([4, 3, 2, 1], $collect->all());

        $collect->pop();
        $this->assertTrue($collect->contains(2));
        $this->assertEqualsCanonicalizing([3, 2, 1], $collect->all());

        $collect->prepend(0);
        $this->assertTrue($collect->contains(3));
        $this->assertEqualsCanonicalizing([3, 2, 1, 0], $collect->all());

        $person = collect([
            'nama'  => 'james',
            'email' => 'james@gmail.com'
        ]);

        $person->pull('email');
        $this->assertArrayHasKey('nama', $person->all());
        $this->assertArrayNotHasKey('email', $person->all());

        $person->put('agama', 'kristen');
        $person->put('alamat', 'sikumana');
        $person->put('email', 'james@gmail.com');

        $this->assertArrayHasKey('nama', $person->all());
        $this->assertArrayHasKey('agama', $person->all());
        $this->assertArrayHasKey('alamat', $person->all());
        $this->assertArrayHasKey('email', $person->all());

        $this->assertIsArray($person->all());

        $this->assertContains('james', $person->all());
        $this->assertContains('kristen', $person->all());
        $this->assertContains('sikumana', $person->all());
        $this->assertContains('james@gmail.com', $person->all());
    }

    public function testMap (): void
    {
        $person = collect([
            [
                'name'          => 'james',
                'email'         => 'james@gmail.com',
                'address'       => 'sikumana',
                'createt_at'    => '15-20-2034',
                'updatet_at'    => '15-20-2034'
            ],
            [
                'name'          => 'dodi',
                'email'         => 'dodi@gmail.com',
                'address'       => 'oesapa',
                'createt_at'    => '15-20-2034',
                'updatet_at'    => '15-20-2034'
            ],
            [
                'name'          => 'erik',
                'email'         => 'erik@gmail.com',
                'address'       => 'tofa',
                'createt_at'    => '15-20-2034',
                'updatet_at'    => '15-20-2034'
            ]
        ]);

        $ressult = $person->map(function ( $item ) {
            return [
                'nama_lengkap'          => $item['name'],
                'email'                 => $item['email'],
                'alamat'                => $item['address'],
                'tanggal_buat'          => $item['createt_at'],
                'tanggal_perbaharui'    => $item['updatet_at']
            ];
        });

        $this->assertContainsEquals('james', $ressult->all()[0]);
        $this->assertContainsEquals('dodi', $ressult->all()[1]);
        $this->assertContainsEquals('erik', $ressult->all()[2]);

        $this->assertContains('james@gmail.com', $ressult->all()[0]);
        $this->assertContains('dodi@gmail.com', $ressult->all()[1]);
        $this->assertContains('erik@gmail.com', $ressult->all()[2]);

        $this->assertContains('15-20-2034', $ressult->all()[1]);

        $ressult->each( function ($item) {
            $this->assertArrayHasKey('nama_lengkap', $item);
            $this->assertArrayHasKey('email', $item);
            $this->assertArrayHasKey('alamat', $item);
            $this->assertArrayHasKey('tanggal_buat', $item);
            $this->assertArrayHasKey('tanggal_perbaharui', $item);
        });

        $this->assertIsArray($ressult->all());
        $this->assertCount(3, $person->all());
    }

    public function testMapInto (): void
    {
        $collect = collect(['sinta', 'dodi', 'erik']);
        $result = $collect->mapInto(Person::class);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);

        $result->each(function ($item) {
            if ($item->nama === 'sinta') {
                $this->assertEqualsCanonicalizing('sinta', $item->nama);
                $this->assertEquals(new Person('sinta'), $item);
            }
            if ($item->nama === 'dodi') {
                $this->assertEqualsCanonicalizing('dodi', $item->nama);
                $this->assertEquals(new Person('dodi'), $item);
            }
            if ($item->nama === 'erik') {
                $this->assertEqualsCanonicalizing('erik', $item->nama);
                $this->assertEquals(new Person('erik'), $item);
            }
        });
    }

    public function testMapSpread (): void
    {
        $array1 = collect([
            ['1', 'sinta', 'sinta@gmail.com', 'sikumana'],
            ['2', 'dodi', 'dodi@gmail.com', 'oesapa'],
            ['3', 'erik', 'erik@gmail.com', 'jalur 40'],
        ]);

        $result = $array1->mapSpread(function ($id, $nama, $email, $alamat) {
            return [
                'id'           => $id,
                'nama_lengkap' => $nama,
                'email'        => $email,
                'alamat'       => $alamat,
            ];
        });

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result->all());

        foreach ($result as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('nama_lengkap', $item);
            $this->assertArrayHasKey('email', $item);
            $this->assertArrayHasKey('alamat', $item);
        }

        assertTrue(!is_null($result));
    }

    public function testMapToGroups (): void
    {
        $collect = collect([
            ['nama' => 'sinta', 'job' => 'developer'],
            ['nama' => 'dodi', 'job' => 'developer'],
            ['nama' => 'erik', 'job' => 'marketing'],
            ['nama' => 'aldo', 'job' => 'security']
        ])->mapToGroups( function ($item, $key) {
            return [$item['job'] => $item['nama']];
        });

        $this->assertCount(2, $collect->get('developer')->all());
        $this->assertContainsEquals('sinta', $collect->get('developer')->all());
        $this->assertContainsEquals('dodi', $collect->get('developer')->all());

        $this->assertCount(1, $collect->get('marketing')->all());
        $this->assertContainsEquals('erik', $collect->get('marketing')->all());

        $this->assertCount(1, $collect->get('security')->all());
        $this->assertContainsEquals('aldo', $collect->get('security')->all());
    }

    public function testZip (): void
    {
        $collect1 = collect([1, 2, 3]);
        $collect2 = collect([4, 5, 6]);
        $collect3 = collect([7, 8, 9]);
        $collect4 = collect([10, 11, 12]);

        $result = $collect1->zip($collect2, $collect3, $collect4);

        $this->assertEquals([
            collect([1, 4, 7, 10]),
            collect([2, 5, 8, 11]),
            collect([3, 6, 9, 12])
        ], $result->all());
    }

    public function testConcat (): void
    {
        $collect1 = collect(['james']);
        $collect2 = collect([
            'nama'  => 'james'
        ]);

        $result1 = $collect1->concat(['sinta'])
                          ->concat(['erik', 'dodi'])
                          ->concat(['nama' => 'putri']);

        $result2 = $collect2->concat(['email' => 'james@gmail.com'])
                            ->concat([
                                'alamat'    => 'sikumana',
                                'agama'     => 'kristen'
                            ]);

        $this->assertTrue(true);
    }

    public function testCombine (): void
    {
        $collect = collect(['nama', 'email']);
        $result = $collect->combine([
            'james', 'james@gmail.com'
        ]);

        $this->assertArrayHasKey('nama', $result->all());
        $this->assertArrayHasKey('email', $result->all());
        $this->assertCount(2, $result->all());
        $this->assertIsArray($result->all());
    }

    public function testCollapse (): void
    {
        $collect = collect([
            ['anggur', 'pepaya', 'melon', 'semangka'],
            ['appel', 'mentimun', 'mangga', 'pepaya'],
            ['lemon', 'naga', 'sirsak', 'kelapa']
        ]);

        $hasil = $collect->collapse();

        $this->assertEqualsCanonicalizing([
            'anggur', 'pepaya', 'melon', 'semangka',
            'appel', 'mentimun', 'mangga', 'pepaya',
            'lemon', 'naga', 'sirsak', 'kelapa'
        ], $hasil->all());

        $this->assertIsArray($hasil->all());
        $this->assertCount(12, $hasil->all());
    }

    public function testFlatMap (): void
    {
        $collect = collect([
            ['nama' => 'james'],
            ['email' => 'james@gmail.com'],
            ['agama' => 'kristen'],
            ['umur' => 21]
        ]);

        $result = $collect->flatMap(function ($item) {
            return $item;
        });

        $this->assertArrayHasKey('nama', $result->all());
        $this->assertArrayHasKey('email', $result->all());
        $this->assertArrayHasKey('agama', $result->all());
        $this->assertArrayHasKey('umur', $result->all());

        $this->assertIsArray($result->all());
        $this->assertCount(4, $result->all());

        $collect1 = collect([
            [
                'nama'  => 'james',
                'hoby'  => ['design', 'coding', 'gaming']
            ],
            [
                'nama'  => 'sinta',
                'hoby'  => ['mendengarkan musik', 'membaca', 'bermain bola']
            ]
        ]);
        $result2 = $collect1->flatMap(function ($item) {
            return $item['hoby'];
        });

        $this->assertCount(6, $result2->all());
        $this->assertContainsEquals('mendengarkan musik', $result2->all());
    }

    public function test (): void
    {
        $collect = collect(['a', 'b', 'c', 'd']);

        $result1 = $collect->join(', ', ', dan ');
        $result2 = $collect->join(', ');
        $result3 = $collect->join(' - ');

        $this->assertEquals('a, b, c, dan d', $result1);
        $this->assertEquals('a, b, c, d', $result2);
        $this->assertEquals('a - b - c - d', $result3);
    }

    public function testFilter (): void
    {
        $collect = collect([
            'sinta' => 80,
            'dodi'  => 79,
            'erik'  => 81,
            'aldo'  => 74,
            'ririn' => 60
        ]);

        $result = $collect->filter(function ($value, $key) {
            return $value >= 75;
        });

        $this->assertCount(3, $result->all());
        $this->assertIsArray($result->all());
        $this->assertInstanceOf(Collection::class, $result);

        $this->assertArrayHasKey('sinta', $result->all());
        $this->assertArrayHasKey('dodi', $result->all());
        $this->assertArrayHasKey('erik', $result->all());

        $collect2 = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $result2 = $collect2->filter(function ($value, $key) {
            return $value % 2 === 0;
        });

        $this->assertCount(5, $result2->all());
        $this->assertIsArray($result->all());
        $this->isInstanceOf(Collection::class, $result2);
        $this->assertEqualsCanonicalizing([2, 4, 6, 8, 10], $result2->all());
    }

    public function testPartition (): void
    {
        $collect = collect([
            'sinta' => 80,
            'dodi'  => 79,
            'erik'  => 81,
            'aldo'  => 74,
            'ririn' => 60
        ]);

        [$nilai_atas, $nilai_bawah] = $collect->partition( function ($item) {
            return $item >= 75;
        });

        $this->assertIsArray($nilai_atas->all());
        $this->assertIsArray($nilai_bawah->all());

        $this->assertInstanceOf(Collection::class, $nilai_atas);
        $this->assertInstanceOf(Collection::class, $nilai_bawah);

        $this->assertCount(3, $nilai_atas->all());
        $this->assertCount(2, $nilai_bawah->all());

        $this->assertArrayHasKey('sinta', $nilai_atas->all());
        $this->assertArrayHasKey('sinta', $nilai_atas->all());
        $this->assertArrayHasKey('dodi', $nilai_atas->all());

        $this->assertArrayHasKey('aldo', $nilai_bawah->all());
        $this->assertArrayHasKey('ririn', $nilai_bawah->all());
    }

    public function testTesting (): void
    {
        $collect = collect(['andi', 'dodi', 'erik', 'sinta']);

        $result = $collect->contains(function ($value, $key) {
            return $value === 'sinta';
        });
        $result2 = $collect->contains('putri');

        $this->assertTrue($result);
        $this->assertFalse($result2);

        $collect2 = collect([
            'nama' => 'sinta',
            'email' => 'sinta@gmail.com',
        ]);

        $result = $collect2->has('nama');
        $result1 = $collect2->has('alamat');

        $this->assertTrue($result);
        $this->assertFalse($result1);

        $resul3 = $collect2->hasAny(['nama', 'agama']);
        $result4 = $collect2->hasAny(['alamat', 'id']);

        $this->assertTrue($resul3);
        $this->assertFalse($result4);
    }

    public function testGroup (): void
    {
        $collect = collect([
            ['nama' => 'james', 'email' => 'james@gmail.com', 'devisi' => 'frond end'],
            ['nama' => 'dodi', 'email' => 'dodi@gmail.com', 'devisi' => 'frond end'],
            ['nama' => 'erik', 'email' => 'erik@gmail.com', 'devisi' => 'back end'],
            ['nama' => 'erwin', 'email' => 'erwin@gmail.com', 'devisi' => 'database'],
        ]);

        $result1 = $collect->groupBy('devisi');
        // var_dump($result1['frond end']);

        $this->assertIsArray($result1->all());
        $this->assertInstanceOf(Collection::class, $result1);

        $this->assertCount(2, $result1['frond end']);
        $this->assertCount(1, $result1['back end']);
        $this->assertCount(1, $result1['database']);

        foreach ($result1['frond end'] as $item) {
            $this->assertArrayHasKey('nama', $item);
            $this->assertArrayHasKey('email', $item);
            $this->assertArrayHasKey('devisi', $item);
        }

        foreach ($result1['frond end'] as $item) {
            $this->assertArrayHasKey('nama', $item);
            $this->assertArrayHasKey('email', $item);
            $this->assertArrayHasKey('devisi', $item);
        }

        foreach ($result1['database'] as $item) {
            $this->assertArrayHasKey('nama', $item);
            $this->assertArrayHasKey('email', $item);
            $this->assertArrayHasKey('devisi', $item);
        }

        $result2 = $collect->groupBy(function ($value, $key) {
            return strtoupper($value['devisi']);
        });

        // var_dump($result2);
        $this->assertTrue(true);
    }

    public function testSlice (): void
    {
        $collect = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $result = $collect->slice(4);

        $this->assertIsArray($result->all());
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(5, $result->all());
        $this->assertEqualsCanonicalizing([5, 6, 7, 8, 9], $result->all());

        $rresult1 = $collect->slice(2, 6);

        $this->assertIsArray($result->all());
        $this->assertInstanceOf(Collection::class, $rresult1);
        $this->assertCount(6, $rresult1->all());
        $this->assertEqualsCanonicalizing([3, 4, 5, 6, 7, 8], $rresult1->all());
    }

    public function testTake (): void
    {
        $collect = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 0]);

        $result1 = $collect->take(5);

        $this->assertIsArray($result1->all());
        $this->assertInstanceOf(Collection::class, $result1);
        $this->assertCount(5, $result1->all());
        $this->assertEqualsCanonicalizing([1, 2, 3, 4, 5], $result1->all());

        $result2 = $collect->takeUntil( function ($value, $key) {
            return $value === 4;
        });

        $this->assertIsArray($result2->all());
        $this->assertInstanceOf(Collection::class, $result2);
        $this->assertCount(3, $result2->all());
        $this->assertEqualsCanonicalizing([1, 2, 3], $result2->all());

        $result3 = $collect->takeWhile(function ($value, $key) {
            return $value <= 4;
        });

        $this->assertIsArray($result3->all());
        $this->assertInstanceOf(Collection::class, $result3);
        $this->assertCount(4, $result3->all());
        $this->assertEqualsCanonicalizing([1, 2, 3, 4], $result3->all());
    }

    public function testSkip (): void
    {
        $collect = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $result1 = $collect->skip(6);

        $this->assertIsArray($result1->all());
        $this->isInstanceOf(Collection::class, $result1);
        $this->assertCount(3, $result1->all());
        $this->assertEqualsCanonicalizing([7, 8, 9], $result1->all());

        $result2 = $collect->skipUntil(function ($value, $key) {
            return $value === 3;
        });

        $this->assertIsArray($result2->all());
        $this->assertInstanceOf(Collection::class, $result2);
        $this->assertCount(7, $result2->all());
        $this->assertEqualsCanonicalizing([3, 4, 5, 6, 7, 8, 9], $result2->all());

        $result3 = $collect->skipWhile(function ($value, $key) {
            return $value <= 6;
        });

        $this->assertIsArray($result3->all());
        $this->assertInstanceOf(Collection::class, $result3);
        $this->assertCount(3, $result3->all());
        $this->assertEqualsCanonicalizing([7, 8, 9], $result3->all());
    }

    public function testChunk (): void
    {
        $collect = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $result = $collect->chunk(3);
        $this->assertIsArray($collect->all());
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result->all());

        $this->assertEqualsCanonicalizing([1, 2, 3], $result->all()[0]->all());
        $this->assertEqualsCanonicalizing([4, 5, 6], $result->all()[1]->all());
        $this->assertEqualsCanonicalizing([7, 8, 9], $result->all()[2]->all());
    }

    public function testFirst (): void
    {
        $collect = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $result = $collect->first();
        $this->assertEquals(1, $result);

        $result1 = $collect->first( function ($value, $key) {
            return $value > 5;
        });
        $this->assertEquals(6, $result1);
    }

    public function testLaset (): void
    {
        $collect = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $result = $collect->last();
        $this->assertEquals(9, $result);

        $result1 = $collect->last( function ($value, $key) {
            return $value > 7;
        });
        $this->assertEquals(9, $result1);
    }

    public function testRandom (): void
    {
        $collect = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $result = $collect->random();
        $this->assertTrue(in_array($result, [1, 2, 3, 4, 5, 6, 7, 8, 9]));
    }

    public function test_checking_extendsion (): void
    {
        $collect = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);
        $this->assertTrue($collect->isNotEmpty());
        $this->assertFalse($collect->isEmpty());
        $this->assertTrue($collect->contains(2));
        $this->assertFalse($collect->contains(12));

        $result = $collect->contains( function ($value, $key) {
            return $value === 4;
        });

        $this->assertTrue($result);
    }

    public function test_sorting (): void
    {
        $collect = collect([5, 3, 9, 6, 2, 1, 8, 4, 7]);

        $result = $collect->sort();

        $this->assertIsArray($result->values()->all());
        $this->assertEqualsCanonicalizing([1, 2, 3, 4, 5, 6, 7, 8, 9] ,$result->values()->all());

        $result1 = $collect->sortDesc();

        $this->assertIsArray($result1->values()->all());
        $this->assertEqualsCanonicalizing([9, 8, 7, 6, 5, 4, 3, 2, 1], $result1->values()->all());
    }

    public function test_agregate (): void
    {
        
    }
}
