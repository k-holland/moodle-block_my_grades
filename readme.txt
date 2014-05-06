My Grades Block for Moodle

Provides display of all enrolled course overall grades and links to grade reports from the My Home page.

To install, place all files in /blocks/my_grades and visit /admin/index.php in your browser.

This block has been tested on the following versions of Moodle: 2.3, 2.4, 2.5 and 2.6.

This block was written by Karen Holland <kholland.dev@gmail.com>, Mei Jin and Jiajia Chen.
It is copyright Karen Holland, Mei Jin and Jiajia Chen and contributors.

The My Grades block is designed to display the exact same basic result as calculated by the Moodle gradebook as the student will see in their own user and overview reports. Due to the multiple variations of grade display types which Moodle allows the teacher to choose from, it would be difficult and would require more system processing to anticipate the exact display type and do additional calculations to the grade value itself. This would create the risk of the My Grades block Grade value being different to the grade value viewed in the student's user or overview reports. It would also increase the load on the Moodle site itself.

Therefore, if the Grade item setting is set to "Real", which it is as default, and the student's real grade for the course is 35/40 for instance, the My Grades block will show 35. The student's user report will show 35, and will also show 87.50% in the percentage column as the dedicated user report is working with a wider range of data.

However, if the Grade item setting is changed to "Real (Percentage)", the My Grades block will use this new format for displaying the grade value, as in the following screenshot:

Hope this helps to clarify the usage of the My Grades block and many thanks to everyone for using it and giving feedback.

Released Under the GNU General Public Licence http://www.gnu.org/copyleft/gpl.html
