<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\Questions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriteriaQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'COMMITMENT' => [
                'questions' => [
                    0 => 'Demonstrates sensitiviy to students` ability to attend and absorb content information',
                    1 => 'Integrates sensitively his/her learning objectives with those of the stduents in a collaborative process',
                    2 => 'Makes self available to students beyond official time',
                    3 => 'Regularly comes to class on time, well-groomed and well-prepared to complete assigned responsibilities',
                    4 => 'Keeps accurate records of students` performance and prompt submission of the same'
                ],
            ],
            'KNOWLEDGE OF SUBJECT' => [
                'questions' => [
                    0 => 'Demonstrates mastery of the subject matter (explain the subject matter without
                    relying solely on the prescribed textbook)',
                    1 => 'Draws and share information on the state of the art of theory and practice on his/her discipline',
                    2 => 'Integrates subject to practical circumstances and learning intents/purposes of students',
                    3 => 'Explains the relevance of present topics to the previous lessons, and relates the subject matter
                    to relevant current issues and/or daily life activities',
                    4 => 'Demonstrates up-to-date knowledge and/or awareness on current trends and issues of the subject'
                ],
            ],
            'TEACHING FOR INDEPENDENT LEARNING' => [
                'questions' => [
                    0 => 'Creates teaching strategies that allow students to practice using concepts they need to understand (interactive discussion)',
                    1 => 'Enhances student self-esteem and/or gives due recognition to students` performance/potentials',
                    2 => 'Allows students to create their own course with objectives and realistically defined student-professor rules and make them accountable for their performance',
                    3 => 'Allows students to think independently and make their own decisions and holding them accountable for their performance based largely on their success in executing decisions',
                    4 => 'Encourages students to learn beyond what is required and help/guide the students how to apply the concepts learned',
                ],
            ],
            'MANAGEMENT OF LEARNING' => [
                'questions' => [
                    0 => 'Creates opportunities for intensive and/or extensive contribution of students in the class activities (e.g breaks class into dyads, triads or buzz/task groups).',
                    1 => 'Assumes roles as facilitator, resource person, coach, inquisitor, integrator, referee in drawing students to contribute to knowledge and understanding of the concepts at hand.',
                    2 => 'Designs and implements learning conditions and experience that promotes healthy exchange and or/confrontrations',
                    3 => 'Structures/re-structures learning and teaching-learning context to enhance attainment of collective learning objectives',
                    4 => 'Use of Instructional Materials (audio/video materials: fieldtrips, film showing, computer aided instruction and etc.) to reinforce learning processes.',
                ],
            ]
        ];

        foreach($data as $criterias => $records) {
            $maxOrderBy = Criteria::max('order_by');

            $criteria = Criteria::create([
                'criteria'      => $criterias,
                'order_by'      => $maxOrderBy + 1,
                'percentage'    => 20,
            ]);


            foreach($records['questions'] as $questions) {
                Questions::create([
                    'criteria_id' => $criteria->id,
                    'question' => $questions,
                ]);
            }

        }
    }
}
