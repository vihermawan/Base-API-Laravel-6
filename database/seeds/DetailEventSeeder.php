<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DetailEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['UGMTalks merupakan event yang diadakan oleh pihak UGM. UGMtalks menghadirkan sejumlah pembicara dari UGM yang memaparkan tentang materi-materi yang bersifat inovatif, terkini dan inspiratif. UGMtalks bertujuan dapat memberikan inspirasi, inovasi, dan memupuk ide-ide baru dari pikiran brilian mahasiswa dan masyarakat. Sehingga, UGMtalks diharapkan dapat memunculkan inovasi dan perspektif baru. UGMtalks juga sangat menantikan pikiran-pikiran brilian yang kalian punya. Anda ingin berpartisipasi? Siapkan diri anda untuk berpartisipasi dengan UGMtalks','2020-05-01','2020-05-15','2020-05-16','2020-05-24','07:00','15:00',10,'Auditorium Pasca Sarjana','Tertutup','UGMTalks.jpg','0','-','-','083849971011','ugmtalks','ugmtalks@mail.ugm.ac.id',14,227], //1
            ['Banyak permasalahan non medis yang dibahas pada event ini, terutama penyakit yang tidak dapat disembuhkan secara medis. Seperti: gangguan makhluk halus, memiliki ilmu turunan, dan juga penyakit yang sudah bertahun tahun yang tidak dapat disembuhkan oleh medis dikarenakan akhlak orang tersebut yang harus diperbaiki dengan cara bertaubat dan berubah menjadi lebih baik lagi.','2020-05-15','2020-06-10','2020-07-12','2020-07-12','19:30','21:00',8,'Masjid Kampus UGM','Tertutup','SQ.jpg','0','-','-','083849971010','siramanqolbu','siramanqolbu@gmail.com',14,227], //2
            ['Seminar ini merupakan puncak acara dari kegiatan Pagelaran Budaya UGM yang termasuk dalam serangkaian agenda Dies Natalis UGM. Seminar ini dimeriahkan oleh bintang tamu spesial yaitu Najwa Sihab, serta dimeriahkan oleh the Next G sebuah grup musik yang beranggotakan dosen-dosen UGM. Bintang tamu pada Seminar ini yakni Sudjiwo Tejo, Wikan Sakarinto, dan masih banyak lagi.','2020-05-15','2020-06-11','2020-06-30','2020-05-30','10:00','17:00',30,'Graha Saba Pramana','Terbuka','UGMTalks.jpg','75000','Mandiri','129829802','083849971010','event','seminarfilm@gmail.com',14,227], //3
            
            ['Salah satu cara untuk mempersiapkan SBMPTN selain belajar adalah menguji kemampuan melalui Tryout. Dengan mengikuti tes uji coba ini kamu bisa setidaknya bisa menjajal “medan perang” lebih awal. Sehingga kamu bisa mempelajari dan menentukan strategi untuk menaklukan SBMPTN','2020-05-11','2020-06-21','2020-06-29','2020-06-29','07:00','15:00',40,'SMA 1 Teladan Yogyakarta','Tertutup','UGMTalks.jpg','0','-','-','083849971010','pejuangsbmptn','pejuangsbmptn@gmail.com',14,227], //4
            ['Seminar ini merupakan puncak acara dari kegiatan Pagelaran Budaya UGM yang termasuk dalam serangkaian agenda Dies Natalis UGM. Seminar ini dimeriahkan oleh bintang tamu spesial yaitu komika Bintang Emon, serta dimeriahkan oleh the Kandang sebuah grup musik yang beranggotakan mahasiswa fakultas Teknik UGM. Bintang tamu pada Seminar ini yakni Sudjiwo Tejo, Wikan Sakarinto, dan masih banyak lagi.','2020-05-11','2020-06-21','2020-06-27','2020-06-27','09:30','17:00',60,'Graha Saba Pramanana','Tertutup','BL.jpg','0','-','-','083849971011','seminarkebudayan','seminarkebudayan@mail.ugm.ac.id',14,227], //5
            ['Career Days merupakan kegiatan yang menyelenggarakan pameran bursa kerja dan on campus recruitment yang diikuti oleh perusahaan-perusahaan dari berbagai industri untuk para job seeker terutama alumni. Career Days memberikan kesempatan yang luas bagi para pencari kerja untuk memperbesar potensi meraih karir dan sekaligus memberikan kemudahan bagi dunia industri dalam memperoleh calon sumber daya manusia yang potensial secara efektif dan efisien.','2020-04-11','2020-05-21','2020-06-25','2020-06-28','08:00','20:00',50,'Graha Saba Pramanana','Terbuka','UGMTalks.jpg','0','-','-','083849971011','carrerdays','carrerdays@mail.ugm.ac.id',14,227], //6
            
            ['Talkshow ini merupakan kegiatan dimana mahasiswa bisa mencari tahu bagaimana mendapatkan beasiswa yang cocok untuk mahasiswa, serta bagaimana cara mahasiswa untuk dapat mengetahui info untuk melakukan ekstend ke luar negeri. Talkshow ini akan menghadirkan beberapa bintang tamu terkenal seperti Tasya Kamila (beasiswa LDPP) dan masih banyak lagi.','2020-04-15','2020-04-24','2020-05-12','2020-05-13','14:30','16:00',30,'Auditorium PascaSarjana','Tertutup','GO.jpg','0','-','-','083849971011','talkshowbeasiswa','talkshowbeasiswa@mail.ugm.ac.id',14,227], //7
            ['BukaTalks merupakan sebuah event yang diadakan oleh Bukalapak sejak tahun 2017. Kali ini, BukaTalks mengangkat tema Real-Time Operational Insight with Automated Monitoring, Altering and Logging dengan narasumber Dian Syahfitra, Software Architect Bukalapak. Menargetkan developer Indonesia sebagai peserta, Dian Syahfitra berbagi informasi mengenai isu operasional di large scale production system Bukalapak dan bagaimana peristiwa-peristiwa tersebut dapat terdeteksi dengan cepat dan otomatis.','2020-05-02','2020-06-21','2020-06-26','2020-06-26','08:30','17:00',75,'Gedung C2 Fakultas Teknik','Tertutup','BL.jpg','0','-','-','083849971011','vihermawan','bukatalks@gmail.com',14,227], //8
            ['MLBB Competition merupakan sebuah kompetisi dimana mahasiswa bisa beradu skill dalam permainan Mobile Legends. Event ini merupakan kegiatan yang dilaksanakan sejak tahun 2017 oleh pihak Mobile Legends. Pemenang akan mendapatkan hadiah sebesar 5 juta untuk juara pertama serta juara 2 dan 3 akan mendapat hadiah 2 juta dan 1 juta. Jadi segera daftarkan timmu sekarang!','2020-05-02','2020-05-06','2020-05-12','2020-05-15','09:30','21:00',100,'Gelanggang Mahasiswa','Tertutup','ml.jpg','0','-','-','083849971011','vihermawan','mlbb@gmail.com',14,227], //9

            ['Lagi seneng banget traveling sambil nyari spot ootd di kota-kota tertentu, atau eksperimen masak terus di bungkus kreatif eh dijual dan laku! Iya emang hobby orang kadang ngga cukup satu. Nah! HAGE mengerti itu, makanya hadir untuk mengerti kamu yang punya banyak hobby dan mau mengekspresikan semuanya. Bukan cuma sekedar pameran, tapi kamu bisa dapet ilmu baru tentang hobby-hobby yang kamu geluti ditambah pengalaman baru dari beragam hobby lainnya. Udah kebayang serunya kaya apa? Jangan cuma dibayangin, dateng dan rasain sendiri 31 Januari – 2 Februari 2020 di ICE BSD yaaa','2019-05-15','2020-05-24','2020-06-15','2020-06-16','14:30','16:00',30,'Auditorium ITB','Tertutup','GO.jpg','0','-','-','01010101','vihermawan','vickyhermawan99@mail.ugm.ac.id',14,227], //10
            ['Lagi seneng banget traveling sambil nyari spot ootd di kota-kota tertentu, atau eksperimen masak terus di bungkus kreatif eh dijual dan laku! Iya emang hobby orang kadang ngga cukup satu. Nah! HAGE mengerti itu, makanya hadir untuk mengerti kamu yang punya banyak hobby dan mau mengekspresikan semuanya. Bukan cuma sekedar pameran, tapi kamu bisa dapet ilmu baru tentang hobby-hobby yang kamu geluti ditambah pengalaman baru dari beragam hobby lainnya. Udah kebayang serunya kaya apa? Jangan cuma dibayangin, dateng dan rasain sendiri 31 Januari – 2 Februari 2020 di ICE BSD yaaa','2020-05-02','2020-05-21','2020-05-25','2020-05-26','16:30','18:00',75,'Gedung C2 Fakultas Teknik','Tertutup','BL.jpg','0','-','-','01010101','vihermawan','vickyhermawan99@mail.ugm.ac.id',14,227], //11
            ['Lagi seneng banget traveling sambil nyari spot ootd di kota-kota tertentu, atau eksperimen masak terus di bungkus kreatif eh dijual dan laku! Iya emang hobby orang kadang ngga cukup satu. Nah! HAGE mengerti itu, makanya hadir untuk mengerti kamu yang punya banyak hobby dan mau mengekspresikan semuanya. Bukan cuma sekedar pameran, tapi kamu bisa dapet ilmu baru tentang hobby-hobby yang kamu geluti ditambah pengalaman baru dari beragam hobby lainnya. Udah kebayang serunya kaya apa? Jangan cuma dibayangin, dateng dan rasain sendiri 31 Januari – 2 Februari 2020 di ICE BSD yaaa','2020-05-02','2020-05-11','2020-06-12','2020-06-15','09:30','21:00',100,'Istora Senayan','Tertutup','ml.jpg','0','-','-','01010101','vihermawan','vickyhermawan99@mail.ugm.ac.id',14,227], //12

            ['Lagi seneng banget traveling sambil nyari spot ootd di kota-kota tertentu, atau eksperimen masak terus di bungkus kreatif eh dijual dan laku! Iya emang hobby orang kadang ngga cukup satu. Nah! HAGE mengerti itu, makanya hadir untuk mengerti kamu yang punya banyak hobby dan mau mengekspresikan semuanya. Bukan cuma sekedar pameran, tapi kamu bisa dapet ilmu baru tentang hobby-hobby yang kamu geluti ditambah pengalaman baru dari beragam hobby lainnya. Udah kebayang serunya kaya apa? Jangan cuma dibayangin, dateng dan rasain sendiri 31 Januari – 2 Februari 2020 di ICE BSD yaaa','2020-05-15','2020-05-24','2020-05-25','2020-05-26','14:30','16:00',30,'Auditorium ITB','Tertutup','GO.jpg','0','-','-','01010101','vihermawan','vickyhermawan99@mail.ugm.ac.id',14,227], //13
            ['Lagi seneng banget traveling sambil nyari spot ootd di kota-kota tertentu, atau eksperimen masak terus di bungkus kreatif eh dijual dan laku! Iya emang hobby orang kadang ngga cukup satu. Nah! HAGE mengerti itu, makanya hadir untuk mengerti kamu yang punya banyak hobby dan mau mengekspresikan semuanya. Bukan cuma sekedar pameran, tapi kamu bisa dapet ilmu baru tentang hobby-hobby yang kamu geluti ditambah pengalaman baru dari beragam hobby lainnya. Udah kebayang serunya kaya apa? Jangan cuma dibayangin, dateng dan rasain sendiri 31 Januari – 2 Februari 2020 di ICE BSD yaaa','2020-05-02','2020-05-26','2020-06-25','2020-06-26','16:30','18:00',75,'Gedung C2 Fakultas Teknik','Tertutup','BL.jpg','0','-','-','01010101','vihermawan','vickyhermawan99@mail.ugm.ac.id',14,227], //14
            ['Lagi seneng banget traveling sambil nyari spot ootd di kota-kota tertentu, atau eksperimen masak terus di bungkus kreatif eh dijual dan laku! Iya emang hobby orang kadang ngga cukup satu. Nah! HAGE mengerti itu, makanya hadir untuk mengerti kamu yang punya banyak hobby dan mau mengekspresikan semuanya. Bukan cuma sekedar pameran, tapi kamu bisa dapet ilmu baru tentang hobby-hobby yang kamu geluti ditambah pengalaman baru dari beragam hobby lainnya. Udah kebayang serunya kaya apa? Jangan cuma dibayangin, dateng dan rasain sendiri 31 Januari – 2 Februari 2020 di ICE BSD yaaa','2020-05-02','2020-05-28','2020-06-12','2020-06-15','09:30','21:00',10,'Istora Senayan','Tertutup','ml.jpg','0','-','-','01010101','vihermawan','vickyhermawan99@mail.ugm.ac.id',14,227], //15

            ['Lagi seneng banget traveling sambil nyari spot ootd di kota-kota tertentu, atau eksperimen masak terus di bungkus kreatif eh dijual dan laku! Iya emang hobby orang kadang ngga cukup satu. Nah! HAGE mengerti itu, makanya hadir untuk mengerti kamu yang punya banyak hobby dan mau mengekspresikan semuanya. Bukan cuma sekedar pameran, tapi kamu bisa dapet ilmu baru tentang hobby-hobby yang kamu geluti ditambah pengalaman baru dari beragam hobby lainnya. Udah kebayang serunya kaya apa? Jangan cuma dibayangin, dateng dan rasain sendiri 31 Januari – 2 Februari 2020 di ICE BSD yaaa','2020-05-15','2020-05-24','2020-05-12','2020-05-13','14:30','16:00',30,'Auditorium ITB','Tertutup','GO.jpg','0','-','-','01010101','vihermawan','vickyhermawan99@mail.ugm.ac.id',14,227], //16
            ['Lagi seneng banget traveling sambil nyari spot ootd di kota-kota tertentu, atau eksperimen masak terus di bungkus kreatif eh dijual dan laku! Iya emang hobby orang kadang ngga cukup satu. Nah! HAGE mengerti itu, makanya hadir untuk mengerti kamu yang punya banyak hobby dan mau mengekspresikan semuanya. Bukan cuma sekedar pameran, tapi kamu bisa dapet ilmu baru tentang hobby-hobby yang kamu geluti ditambah pengalaman baru dari beragam hobby lainnya. Udah kebayang serunya kaya apa? Jangan cuma dibayangin, dateng dan rasain sendiri 31 Januari – 2 Februari 2020 di ICE BSD yaaa','2020-05-02','2020-05-26','2020-05-16','2020-05-17','16:30','18:00',75,'Gedung C2 Fakultas Teknik','Tertutup','BL.jpg','0','-','-','01010101','vihermawan','vickyhermawan99@mail.ugm.ac.id',14,227], //17
            ['Lagi seneng banget traveling sambil nyari spot ootd di kota-kota tertentu, atau eksperimen masak terus di bungkus kreatif eh dijual dan laku! Iya emang hobby orang kadang ngga cukup satu. Nah! HAGE mengerti itu, makanya hadir untuk mengerti kamu yang punya banyak hobby dan mau mengekspresikan semuanya. Bukan cuma sekedar pameran, tapi kamu bisa dapet ilmu baru tentang hobby-hobby yang kamu geluti ditambah pengalaman baru dari beragam hobby lainnya. Udah kebayang serunya kaya apa? Jangan cuma dibayangin, dateng dan rasain sendiri 31 Januari – 2 Februari 2020 di ICE BSD yaaa','2020-05-02','2020-05-28','2020-06-12','2020-06-15','09:30','21:00',100,'Istora Senayan','Tertutup','ml.jpg','0','-','-','01010101','vihermawan','vickyhermawan99@mail.ugm.ac.id',14,227], //18

            ['Lagi seneng banget traveling sambil nyari spot ootd di kota-kota tertentu, atau eksperimen masak terus di bungkus kreatif eh dijual dan laku! Iya emang hobby orang kadang ngga cukup satu. Nah! HAGE mengerti itu, makanya hadir untuk mengerti kamu yang punya banyak hobby dan mau mengekspresikan semuanya. Bukan cuma sekedar pameran, tapi kamu bisa dapet ilmu baru tentang hobby-hobby yang kamu geluti ditambah pengalaman baru dari beragam hobby lainnya. Udah kebayang serunya kaya apa? Jangan cuma dibayangin, dateng dan rasain sendiri 31 Januari – 2 Februari 2020 di ICE BSD yaaa','2020-05-15','2020-05-24','2020-05-25','2020-05-26','14:30','16:00',30,'Auditorium ITB','Tertutup','GO.jpg','0','-','-','01010101','vihermawan','vickyhermawan99@mail.ugm.ac.id',14,227], //19
            ['Lagi seneng banget traveling sambil nyari spot ootd di kota-kota tertentu, atau eksperimen masak terus di bungkus kreatif eh dijual dan laku! Iya emang hobby orang kadang ngga cukup satu. Nah! HAGE mengerti itu, makanya hadir untuk mengerti kamu yang punya banyak hobby dan mau mengekspresikan semuanya. Bukan cuma sekedar pameran, tapi kamu bisa dapet ilmu baru tentang hobby-hobby yang kamu geluti ditambah pengalaman baru dari beragam hobby lainnya. Udah kebayang serunya kaya apa? Jangan cuma dibayangin, dateng dan rasain sendiri 31 Januari – 2 Februari 2020 di ICE BSD yaaa','2020-05-02','2020-05-26','2020-06-20','2020-06-22','16:30','18:00',75,'Gedung C2 Fakultas Teknik','Tertutup','BL.jpg','0','-','-','01010101','vihermawan','vickyhermawan99@mail.ugm.ac.id',14,227], //20
            ['Lagi seneng banget traveling sambil nyari spot ootd di kota-kota tertentu, atau eksperimen masak terus di bungkus kreatif eh dijual dan laku! Iya emang hobby orang kadang ngga cukup satu. Nah! HAGE mengerti itu, makanya hadir untuk mengerti kamu yang punya banyak hobby dan mau mengekspresikan semuanya. Bukan cuma sekedar pameran, tapi kamu bisa dapet ilmu baru tentang hobby-hobby yang kamu geluti ditambah pengalaman baru dari beragam hobby lainnya. Udah kebayang serunya kaya apa? Jangan cuma dibayangin, dateng dan rasain sendiri 31 Januari – 2 Februari 2020 di ICE BSD yaaa','2019-05-02','2020-05-28','2020-06-12','2020-06-15','09:30','21:00',100,'Istora Senayan','Tertutup','ml.jpg','0','-','-','01010101','vihermawan','vickyhermawan99@mail.ugm.ac.id',14,227], //21
        ];

        for ($i=0; $i < count($data); $i++) {
            $deskripsi_event = $data[$i][0];
            $open_registration = $data[$i][1];
            $end_registration = $data[$i][2];
            $start_event = $data[$i][3];
            $end_event = $data[$i][4];
            $time_start = $data[$i][5];
            $time_end = $data[$i][6];
            $limit_participant = $data[$i][7];
            $lokasi = $data[$i][8];
            $venue = $data[$i][9];
            $picture = $data[$i][10];
            $biaya = $data[$i][11];
            $bank = $data[$i][12];
            $nomor_rekening = $data[$i][13];
            $telepon = $data[$i][14];
            $instagram =$data[$i][15];
            $email_event = $data[$i][16];
            $id_provinsi = $data[$i][17];
            $id_kabupaten = $data[$i][18];
            $created_at = Carbon::now();
            $updated_at = Carbon::now();

            DB::table('detail_event')->insert([
                'deskripsi_event' => $deskripsi_event, 
                'start_event' => $start_event, 
                'end_event' => $end_event, 
                'open_registration' => $open_registration, 
                'end_registration' => $end_registration, 
                'time_start' => $time_start, 
                'time_end' => $time_end, 
                'limit_participant' => $limit_participant, 
                'lokasi' => $lokasi, 
                'venue' => $venue, 
                'picture' => $picture, 
                'biaya' => $biaya,
                'bank' => $bank,
                'nomor_rekening' => $nomor_rekening,
                'telepon' => $telepon,
                'instagram' => $instagram,
                'email_event' => $email_event,
                'id_provinsi' => $id_provinsi,
                'id_kabupaten' => $id_kabupaten,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        }
    }
}
