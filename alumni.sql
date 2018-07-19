-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Φιλοξενητής: 127.0.0.1
-- Χρόνος δημιουργίας: 02 Ιουλ 2018 στις 12:05:22
-- Έκδοση διακομιστή: 10.1.25-MariaDB
-- Έκδοση PHP: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `alumni`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `alumni_cv`
--

CREATE TABLE `alumni_cv` (
  `id` int(11) NOT NULL,
  `alumni_id` int(11) NOT NULL,
  `pdf_src` text CHARACTER SET utf8 NOT NULL,
  `original_name` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Άδειασμα δεδομένων του πίνακα `alumni_cv`
--

INSERT INTO `alumni_cv` (`id`, `alumni_id`, `pdf_src`, `original_name`) VALUES
(1, 2, 'cvPdfUser2.pdf', 'test.pdf');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `body` text NOT NULL,
  `publication_date` date DEFAULT NULL,
  `publication_id` int(11) NOT NULL,
  `published_index_page` tinyint(1) NOT NULL,
  `published_department1` tinyint(1) NOT NULL,
  `published_department2` tinyint(1) NOT NULL,
  `published_department3` tinyint(1) NOT NULL,
  `published_department4` tinyint(1) NOT NULL,
  `published_department5` tinyint(1) NOT NULL,
  `published_department6` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `contents`
--

INSERT INTO `contents` (`id`, `userID`, `status`, `title`, `description`, `body`, `publication_date`, `publication_id`, `published_index_page`, `published_department1`, `published_department2`, `published_department3`, `published_department4`, `published_department5`, `published_department6`) VALUES
(1, 1, 1, 'Η πρώτη Ανακοίνωση', 'Η περιγραφή του Lorem Ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old', '2018-05-30', 1, 1, 1, 0, 0, 0, 1, 0),
(6, 1, 1, 'ακόμα μια ανακοίνωση με εικόνες ', 'Το Lorem Ipsum με εικόνες', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old', '2018-05-31', 2, 1, 0, 0, 0, 0, 1, 1),
(7, 2, 0, 'Ανακοίνωση προς έγκριση', 'Lorem Ipsum ', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old', NULL, 0, 1, 0, 1, 0, 0, 0, 0),
(8, 1, 0, 'Μια μη δημοσιευμένη ανακοίνωση (η οποία κατέβηκε από τον διαχειριστή)', 'Lorem Ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old', '2018-06-19', 3, 0, 1, 0, 0, 1, 0, 0),
(9, 2, 0, 'Μια μη δημοσιευμένη ανακοίνωση (η οποία απορρίφθηκε από τον διαχειριστή)', 'Lorem Ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old', '0000-00-00', 0, 1, 0, 0, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `facultyid` int(11) NOT NULL,
  `dname` text CHARACTER SET greek NOT NULL,
  `nav_color` text NOT NULL,
  `promp_text` text CHARACTER SET utf8 NOT NULL,
  `about_text` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Άδειασμα δεδομένων του πίνακα `departments`
--

INSERT INTO `departments` (`id`, `facultyid`, `dname`, `nav_color`, `promp_text`, `about_text`) VALUES
(1, 1, 'Τμήμα Μηχανικών Πληροφορικής και Τηλεπικοινωνιών', '#cc6666', 'Καλώς ορίσατε στην σελίδα των αποφοίτων του τμήματος Μηχανικών Πληροφορικής και Τηλεπικοινωνιών', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old'),
(2, 1, 'Τμήμα Μηχανολόγων Μηχανικών', '#cc9966', 'Καλώς ορίσατε στην σελίδα των αποφοίτων του τμήματος Μηχανολόγων Μηχανικών', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old'),
(3, 1, 'Τμήμα Μηχανικών Περιβάλλοντος', '#00b33c', 'Καλώς ορίσατε στην σελίδα των αποφοίτων του τμήματος Μηχανικών Περιβάλλοντος', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old'),
(4, 3, 'Παιδαγωγικό Τμήμα Δημοτικής Εκπαίδευσης', '#cc5200', 'Καλώς ορίσατε στην σελίδα των αποφοίτων του τμήματος Παιδαγωγικό Δημοτικής Εκπαίδευσης', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old'),
(5, 3, 'Παιδαγωγικό Τμήμα Νηπιαγωγών ', '#008080', 'Καλώς ορίσατε στην σελίδα των αποφοίτων του τμήματος Παιδαγωγικό Νηπιαγωγών', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old'),
(6, 2, 'Τμήμα Εικαστικών και Εφαρμοσμένων Τεχνών', '#999966', 'Καλώς ορίσατε στην σελίδα των αποφοίτων του τμήματος Εικαστικών και Εφαρμοσμένων Τεχνών.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `ekkremothtes`
--

CREATE TABLE `ekkremothtes` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `ekkremothtes`
--

INSERT INTO `ekkremothtes` (`id`, `admin_id`, `content`) VALUES
(1, 1, 'Αυτή η εκκρεμότητα είναι για testing'),
(2, 1, 'Η δεύτερη εκκρεμότητα είναι για να δούμε πως φαίνεται'),
(4, 1, 'Η πρώτη εκκρεμότητα από το πληκτρολόγιο που τροποποιήθηκε μετά την αποθήκευση'),
(5, 1, 'μια δεύτερη εκκρεμότητα');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `email_table`
--

CREATE TABLE `email_table` (
  `id` int(11) NOT NULL,
  `recipient` text CHARACTER SET utf8 NOT NULL,
  `subject` text CHARACTER SET utf8 NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `header` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Άδειασμα δεδομένων του πίνακα `email_table`
--

INSERT INTO `email_table` (`id`, `recipient`, `subject`, `message`, `header`) VALUES
(1, 'tsilis93@gmail.com', 'Η πρώτη Ανακοίνωση', 'This is a MIME encoded message.\r\n\r\n--91bab07d7736106614e899feee754e46\r\nContent-type: text/plain;charset=utf-8\r\n\r\nΗ πρώτη ΑνακοίνωσηΠροσπαθήστε να κάνετε κάποιες αλλαγές. Προσθέστε κάποιο κείμενο ή επιλέξτε κάποια εικόνα. για την πρώτη ανακοίνωση.&nbsp;\r\n\r\n--91bab07d7736106614e899feee754e46\r\nContent-type: text/html;charset=utf-8\r\n\r\n<h3 style=\"text-align: center;\">Η πρώτη Ανακοίνωση</h3><p>Προσπαθήστε να κάνετε κάποιες αλλαγές. Προσθέστε κάποιο κείμενο ή επιλέξτε κάποια εικόνα. για την πρώτη ανακοίνωση.&nbsp;</p><p><img src=\"https://localhost/diplomatiki/content_images/Penguins1527760657.jpg\"><br></p>\r\n\r\n--91bab07d7736106614e899feee754e46--', 'From: <webmaster@example.com>\r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed;boundary=91bab07d7736106614e899feee754e46\r\n'),
(2, 'tasos_kar@yahoo.com', 'Η πρώτη Ανακοίνωση', 'This is a MIME encoded message.\r\n\r\n--91bab07d7736106614e899feee754e46\r\nContent-type: text/plain;charset=utf-8\r\n\r\nΗ πρώτη ΑνακοίνωσηΠροσπαθήστε να κάνετε κάποιες αλλαγές. Προσθέστε κάποιο κείμενο ή επιλέξτε κάποια εικόνα. για την πρώτη ανακοίνωση.&nbsp;\r\n\r\n--91bab07d7736106614e899feee754e46\r\nContent-type: text/html;charset=utf-8\r\n\r\n<h3 style=\"text-align: center;\">Η πρώτη Ανακοίνωση</h3><p>Προσπαθήστε να κάνετε κάποιες αλλαγές. Προσθέστε κάποιο κείμενο ή επιλέξτε κάποια εικόνα. για την πρώτη ανακοίνωση.&nbsp;</p><p><img src=\"https://localhost/diplomatiki/content_images/Penguins1527760657.jpg\"><br></p>\r\n\r\n--91bab07d7736106614e899feee754e46--', 'From: <webmaster@example.com>\r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed;boundary=91bab07d7736106614e899feee754e46\r\n'),
(3, 'tasos_kar@yahoo.com', 'ένα email για να δοκιμάσουμε την αφαίρεση', '$(\'#myTableRow\').remove();', 'From: <webmaster@example.com>\r\n');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `faculties`
--

CREATE TABLE `faculties` (
  `id` int(11) NOT NULL,
  `facultyname` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Άδειασμα δεδομένων του πίνακα `faculties`
--

INSERT INTO `faculties` (`id`, `facultyname`) VALUES
(1, 'Πολυτεχνική Σχολή '),
(2, 'Σχολή Καλών Τεχνών'),
(3, 'Παιδαγωγική Σχολή');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `images_path` varchar(100) NOT NULL,
  `departmentID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `storyID` int(11) NOT NULL,
  `contentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Άδειασμα δεδομένων του πίνακα `images`
--

INSERT INTO `images` (`id`, `images_path`, `departmentID`, `userID`, `storyID`, `contentID`) VALUES
(1, 'plhroforiki1.jpg', 1, 0, 0, 0),
(2, 'plhroforiki2.jpg', 1, 0, 0, 0),
(3, 'mhxanologos1.jpg', 2, 0, 0, 0),
(4, 'mhxanologos2.jpg', 2, 0, 0, 0),
(5, 'peribalontos1.jpg', 3, 0, 0, 0),
(6, 'peribalontos2.jpg', 3, 0, 0, 0),
(7, 'daskales1.jpg', 4, 0, 0, 0),
(8, 'daskales2.jpg', 4, 0, 0, 0),
(9, 'nhpiagwgos1.jpg', 5, 0, 0, 0),
(10, 'nhpiagwgos2.jpg', 5, 0, 0, 0),
(11, 'texnes1.jpg', 6, 0, 0, 0),
(12, 'texnes2.jpg', 6, 0, 0, 0),
(19, 'Koala1527760657.jpg', 0, 0, 0, 6),
(20, 'Lighthouse1527760657.jpg', 0, 0, 0, 6),
(21, 'Penguins1527760657.jpg', 0, 0, 0, 6),
(22, 'Desert1527776799.jpg', 0, 0, 2, 0),
(23, 'Hydrangeas1527776799.jpg', 0, 0, 2, 0),
(24, 'imageUser2.jpg', 0, 2, 0, 0),
(25, 'imageUser1.png', 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL,
  `alumni_id` int(11) NOT NULL,
  `option_id1` int(11) NOT NULL,
  `option_id2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Άδειασμα δεδομένων του πίνακα `newsletter`
--

INSERT INTO `newsletter` (`id`, `alumni_id`, `option_id1`, `option_id2`) VALUES
(1, 1, 1, 0),
(2, 2, 1, 0);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `newsletter_categories`
--

CREATE TABLE `newsletter_categories` (
  `id` int(11) NOT NULL,
  `category_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `newsletter_categories`
--

INSERT INTO `newsletter_categories` (`id`, `category_name`) VALUES
(1, 'Νέα του Πανεπιστημίου'),
(2, 'Προτάσεις Εργασίας - Συνεργασίας');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `newsletter_content`
--

CREATE TABLE `newsletter_content` (
  `id` int(11) NOT NULL,
  `titlos` text NOT NULL,
  `body_html` text NOT NULL,
  `date_created` date NOT NULL,
  `option_id1` int(11) NOT NULL,
  `option_id2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `newsletter_content`
--

INSERT INTO `newsletter_content` (`id`, `titlos`, `body_html`, `date_created`, `option_id1`, `option_id2`) VALUES
(1, 'Μήνυμα καλωσορίσματος για την αναζήτηση νέων χρηστών ', '<p>Οι απόφοιτοί μας που επιθυμούν να <b>ενεργοποιήσουν</b> την καρτέλα με τα στοιχεία τους και να μας δώσουν την απαραίτητη έγκριση για τη δημοσίευσή τους, θα πρέπει να επικοινωνήσουν μαζί μας στέλνοντας email στους διαχειριστές του ιστοχώρου αναφέροντας, το ονοματεπώνυμο τους, το αριθμό μητρώου φοιτητή (ΑΕΜ) που είχαν στα χρόνια φοίτησης και το τμήμα στο οποίο φοίτησαν, ώστε να τους αποσταλεί το username και ο κωδικός επεξεργασίας του προφίλ τους.</p>', '2018-06-05', 1, 0),
(2, 'Ποιος σχεδίασε την σελίδα', '<p>Ο ιστότοπος σχεδιάστηκε και υλοποιήθηκε από τον απόφοιτο του τμήματος Μηχανικών Πληροφορικής και Τηλεπικοινωνιών, Βασίλη Τσιλιμπάρη, στα πλαίσια της διπλωματικής εργασίας του, υπό την επίβλεψη του λέκτορα καθηγητή του αντίστοιχου τμήματος Μηνά Δασυγένη. <a href=\"http://arch.icte.uowm.gr\">(http://arch.icte.uowm.gr)</a></p>', '2018-06-06', 1, 0),
(3, 'Η πρώτη Ανακοίνωση', '<h3 style=\"text-align: center;\">Η πρώτη Ανακοίνωση</h3><p>Προσπαθήστε να κάνετε κάποιες αλλαγές. Προσθέστε κάποιο κείμενο ή επιλέξτε κάποια εικόνα. για την πρώτη ανακοίνωση.&nbsp;</p><p><img src=\"https://localhost/diplomatiki/content_images/Penguins1527760657.jpg\"><br></p>', '2018-06-06', 1, 0);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `alumni_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Άδειασμα δεδομένων του πίνακα `notifications`
--

INSERT INTO `notifications` (`id`, `text`, `alumni_id`, `admin_id`) VALUES
(1, 'Ένας νέος απόφοιτος με όνομα \'Βασίλης\' έκανε αίτηση εγγραφής στο \'Τμήμα Μηχανικών Πληροφορικής και Τηλεπικοινωνιών\'', 0, 1),
(2, 'Μια ειδοποίηση για testing', 2, 0),
(3, 'Η ιστορία με τίτλο \'ακόμα μια ιστορία με εικόνες\' εγκρίθηκε και δημοσιεύτηκε από τον διαχειριστή του συστήματος', 2, 0),
(4, 'Ο απόφοιτος Καράμπελας Τασος ανέβασε νέα Ιστορία', 1, 0),
(5, 'Ο απόφοιτος Καράμπελας Τασος ανανέωσε τα προσωπικά δεδομένα του', 1, 0),
(6, 'Ο απόφοιτος Καράμπελας Τασος ανανέωσε τα προσωπικά δεδομένα του', 1, 0);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `stories`
--

CREATE TABLE `stories` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `definition` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `body` text NOT NULL,
  `comments` text NOT NULL,
  `publication_date` date DEFAULT NULL,
  `publication_id` int(11) NOT NULL,
  `published_department1` tinyint(1) NOT NULL,
  `published_department2` tinyint(1) NOT NULL,
  `published_department3` tinyint(1) NOT NULL,
  `published_department4` tinyint(1) NOT NULL,
  `published_department5` tinyint(1) NOT NULL,
  `published_department6` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `stories`
--

INSERT INTO `stories` (`id`, `userID`, `status`, `definition`, `title`, `description`, `body`, `comments`, `publication_date`, `publication_id`, `published_department1`, `published_department2`, `published_department3`, `published_department4`, `published_department5`, `published_department6`) VALUES
(1, 1, 1, 1, 'Η πρώτη Ιστορία', 'Περιγραφή του Lorem Ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old', '', '2018-06-15', 1, 1, 0, 0, 0, 0, 0),
(2, 2, 1, 1, 'ακόμα μια ιστορία με εικόνες', 'Περιγραφή του Lorem Ipsum με εικόνες', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old', '', '2018-06-15', 2, 1, 1, 0, 1, 0, 0),
(3, 1, 0, 1, 'Μια ιστορία προς έγκριση', 'Lorem Ipsum', '\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old', '', NULL, 0, 1, 0, 0, 1, 0, 0),
(4, 1, 0, 1, 'Μια δημοσιευμένη ιστορία (η οποία κατέβηκε)', 'Lorem Ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old', '', '2018-06-13', 3, 1, 1, 0, 0, 0, 0),
(5, 2, 0, 2, 'Μια μη δημοσιευμένη ιστορία (η οποία απορρίφθηκε)', 'Lorem Ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old', '', '0000-00-00', 0, 1, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `lastname` text NOT NULL,
  `fathers_name` text NOT NULL,
  `email` text NOT NULL,
  `registration_year` year(4) NOT NULL,
  `department_id` int(11) NOT NULL,
  `aem` text NOT NULL,
  `graduation_date` date NOT NULL,
  `graduation_year` year(4) NOT NULL,
  `degree_grade` float NOT NULL,
  `birthday_date` date NOT NULL,
  `phone` text NOT NULL,
  `cell_phone` text NOT NULL,
  `residence_city` text NOT NULL,
  `linkedin` text NOT NULL,
  `facebook` text NOT NULL,
  `instagram` text NOT NULL,
  `twitter` text NOT NULL,
  `google` text NOT NULL,
  `youtube` text NOT NULL,
  `social` text NOT NULL,
  `diploma_thesis_topic` text NOT NULL,
  `job` int(11) NOT NULL,
  `Workpiece` text NOT NULL,
  `job_city` text NOT NULL,
  `metaptuxiako` text NOT NULL,
  `didaktoriko` text NOT NULL,
  `change_password` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `hash` text NOT NULL,
  `messageToadmin` text NOT NULL,
  `role` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `fathers_name`, `email`, `registration_year`, `department_id`, `aem`, `graduation_date`, `graduation_year`, `degree_grade`, `birthday_date`, `phone`, `cell_phone`, `residence_city`, `linkedin`, `facebook`, `instagram`, `twitter`, `google`, `youtube`, `social`, `diploma_thesis_topic`, `job`, `Workpiece`, `job_city`, `metaptuxiako`, `didaktoriko`, `change_password`, `created_by`, `active`, `hash`, `messageToadmin`, `role`, `username`, `password`) VALUES
(1, 'Βασίλης', 'Τσιλιμπάρης', '', 'tsilis93@gmail.com', 2011, 1, '552', '2018-10-08', 2018, 0, '1993-09-28', '', '6981123307', '', '', '', '', '', '', '', '', '', 0, '', '', '', '', 1, 0, 1, '', '', 3, 'admin', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec'),
(2, 'Τασος', 'Καράμπελας', 'Θανάσης', 'tasos_kar@yahoo.com', 2011, 1, '444', '2018-07-05', 2018, 0, '1993-07-27', '2107795222', '6987423991', 'Αθήνα', '', '', '', '', '', '', '', '', 1, '', 'Αθήνα', '', '', 0, 1, 1, '', '', 1, 'KarampelasTasos', '1721018d35c2d82e887b2ac392f1ef0743ad7772874433f41242ceedec700ab8809b47a04975b21bef0f4002545feda0c1078bec60003f0cefdb62eb0d96f1f0'),
(3, 'Μηνάς', 'Δασυγένης', '', 'mdasyg@vlsi.gr', 0000, 0, '', '0000-00-00', 0000, 0, '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', 0, '', '', '', '', 1, 0, 1, '', '', 2, 'DasugenisMinas', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `user_relationship`
--

CREATE TABLE `user_relationship` (
  `id` int(11) NOT NULL,
  `alumni_id` int(11) NOT NULL,
  `friend_alumni_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Άδειασμα δεδομένων του πίνακα `user_relationship`
--

INSERT INTO `user_relationship` (`id`, `alumni_id`, `friend_alumni_id`) VALUES
(2, 1, 2),
(3, 2, 1);

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `alumni_cv`
--
ALTER TABLE `alumni_cv`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `ekkremothtes`
--
ALTER TABLE `ekkremothtes`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `email_table`
--
ALTER TABLE `email_table`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `newsletter_categories`
--
ALTER TABLE `newsletter_categories`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `newsletter_content`
--
ALTER TABLE `newsletter_content`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `user_relationship`
--
ALTER TABLE `user_relationship`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `alumni_cv`
--
ALTER TABLE `alumni_cv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT για πίνακα `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT για πίνακα `ekkremothtes`
--
ALTER TABLE `ekkremothtes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT για πίνακα `email_table`
--
ALTER TABLE `email_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT για πίνακα `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT για πίνακα `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT για πίνακα `newsletter_categories`
--
ALTER TABLE `newsletter_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT για πίνακα `newsletter_content`
--
ALTER TABLE `newsletter_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT για πίνακα `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT για πίνακα `stories`
--
ALTER TABLE `stories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT για πίνακα `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT για πίνακα `user_relationship`
--
ALTER TABLE `user_relationship`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
