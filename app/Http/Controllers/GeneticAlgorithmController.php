<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Classes;
use App\Models\Subjects;
use App\Models\Faculties;
use App\Models\TimeSlots;
use Illuminate\Http\Request;
use App\Services\RoomService;
use App\Services\ClassesService;
use App\Services\FacultyService;
use App\Models\SubjectAssignment;
use App\Services\TimeSlotService;
use App\Rules\ClassRoomAvailabilityRule;
use App\Rules\FacultyAvailabilityRule;
use App\Rules\RoomAvailabilityRule;
use App\Services\SubjectAssignmentService;

class GeneticAlgorithmController extends Controller
{

    public function generateSchedule(
        $option,
        $id,
        SubjectAssignmentService $subjectAssignmentService,
        RoomService $roomService,
        TimeSlotService $timeSlotService,
        FacultyService $facultyService,
        ClassesService $classesService
    ) {
        ini_set('max_execution_time', 120);
        $classes = null;
        $rooms = null;
        // $timeSlots = null;

        if ($option == 'DEPARTMENT') {
            $classes = $subjectAssignmentService->getAllSubjectAssignmentByDepartment($id);
            $rooms = $roomService->getAllRoomByDepartment($id);
        } else if ($option == 'FACULTY') {
            $classes = $subjectAssignmentService->getAllSubjectAssignmentByFaculty($id);
            $faculty = $facultyService->getFacultiesById($id);
            $rooms = $roomService->getAllRoomByDepartment($faculty->department_id);
        } else if ($option == 'CLASS') {
            $classes = $subjectAssignmentService->getAllSubjectAssignmentByClassHasNoClassSchedules($id);
            // $class = $classesService->getClassesById($id);
            $rooms = $roomService->getAllRoom();
        }

        $timeSlots = $timeSlotService->getAllTimeSlot();

        // Initialize population size and maximum generations
        $populationSize = 5;
        $maxGenerations = 20;

        // Initialize best schedule and its fitness score
        $bestSchedule = null;
        $bestFitness = -1;

        // Genetic algorithm loop
        for ($generation = 0; $generation < $maxGenerations; $generation++) {
            // Generate initial population
            $population = $this->initializePopulation($populationSize, $classes, $rooms, $timeSlots);

            // Evaluate fitness of each schedule in population
            foreach ($population as $schedule) {
                $fitness = $this->evaluateFitness($schedule);
                // Update best schedule if necessary
                if ($fitness > $bestFitness) {
                    $bestFitness = $fitness;
                    $bestSchedule = $schedule;
                }
            }

            // Apply genetic operations: selection, crossover, mutation
            $population = $this->evolvePopulation($population);

            // Log the best fitness and schedule for this generation
            // You can add logging functionality here if needed

        }

        // Return the best schedule generated by the genetic algorithm
        return response()->json([
            'message' => 'Schedule generated successfully',
            'schedule' => $bestSchedule,
            'bestFitness' => $bestFitness
        ]);
    }

    public function suggestRoomAndTimeSlot(
        $saId,
        $facultyId,
        SubjectAssignmentService $subjectAssignmentService,
        RoomService $roomService,
        TimeSlotService $timeSlotService,
        FacultyService $facultyService,
    ) {
        $classes = $subjectAssignmentService->getSubjectAssignmentByIdAll((int) $saId);
        $faculty = $facultyService->getFacultiesById((int) $facultyId);
        $rooms = $roomService->getAllRoomByDepartment($faculty->department_id);
        $timeSlots = $timeSlotService->getAllTimeSlot();

        // Initialize population size and maximum generations
        $populationSize = 1;
        $maxGenerations = 20;

        // Initialize best schedule and its fitness score
        $bestSchedule = null;
        $bestFitness = -1;

        // Genetic algorithm loop
        for ($generation = 0; $generation < $maxGenerations; $generation++) {
            // Generate initial population
            $population = $this->initializePopulation($populationSize, $classes, $rooms, $timeSlots);

            // Evaluate fitness of each schedule in population
            foreach ($population as $schedule) {
                $fitness = $this->evaluateFitness($schedule);
                // Update best schedule if necessary
                if ($fitness > $bestFitness) {
                    $bestFitness = $fitness;
                    $bestSchedule = $schedule;
                }
            }
            $population = $this->evolvePopulation($population);
        }

        // Return the best schedule generated by the genetic algorithm
        return response()->json([
            'schedule' => $bestSchedule,
        ]);
    }

    // Helper method to initialize population with random schedules
    private function initializePopulation($populationSize, $classes, $rooms, $timeSlots)
    {
        $population = [];
        for ($i = 0; $i < $populationSize; $i++) {
            // Generate a random schedule
            $schedule = $this->generateRandomSchedule($classes, $rooms, $timeSlots);
            $population[] = $schedule;
        }
        return $population;
    }

    // Helper method to generate a random schedule
    private function generateRandomSchedule($classes, $rooms, $timeSlots)
    {
        $schedule = [];

        foreach ($classes as $class) {
            // Randomly select an instructor, room, time slot, and day for each class
            $instructor = $class->faculty_id;
            $room = $rooms->random();
            $timeSlot = $timeSlots->random();

            // Get the department ID of the instructor
            $faculty = Faculties::find($instructor);
            $departmentId = $faculty->department_id;

            // Filter rooms based on the department ID
            $filteredRooms = $rooms->where('department_id', $departmentId);

            // Randomly select a room from the filtered rooms
            $room = $filteredRooms->random();

            // Add the class, instructor, room, time slot, and day to the schedule
            $schedule[] = [
                'class' => $class,
                'instructor' => $instructor,
                'room' => $room,
                'time_slot' => $timeSlot,
            ];
        }

        return $schedule;
    }

    private function evaluateFitness($schedule)
    {
        $conflicts = 0;
        $scheduledSlots = [];
        $classScheduledSlots = [];
        $instructorScheduledSlots = [];

        // Initialize arrays to store availability data for batch processing
        $classAvailabilityData = [];
        $roomAvailabilityData = [];
        $facultyAvailabilityData = [];

        // Populate availability data arrays for batch processing
        foreach ($schedule as $class) {
            $classAvailabilityData[] = [
                'class_id' => $class['class']->class_id,
                'time_slot_id' => $class['time_slot']->id,
            ];
            $roomAvailabilityData[] = [
                'room_id' => $class['room']->id,
                'time_slot_id' => $class['time_slot']->id,
            ];
            $facultyAvailabilityData[] = [
                'faculty_id' => $class['class']->faculty_id,
                'time_slot_id' => $class['time_slot']->id,
            ];
        }

        // Batch process availability checks
        $classAvailabilities = $this->batchValidateClassRoomAvailability($classAvailabilityData);
        $roomAvailabilities = $this->batchValidateRoomAvailability($roomAvailabilityData);
        $facultyAvailabilities = $this->batchValidateFacultyAvailability($facultyAvailabilityData);

        // Check for conflicts in the schedule
        foreach ($schedule as $class) {
            $classId = $class['class']->class_id;
            $roomId = $class['room']->id;
            $timeSlotId = $class['time_slot']->id;
            $instructorId = $class['instructor'];

            // Check if the room and time slot are double-booked
            foreach ($schedule as $otherClass) {
                if ($class !== $otherClass) {
                    // Check for overlapping time slots and rooms
                    if (
                        $timeSlotId === $otherClass['time_slot']->id &&
                        $roomId === $otherClass['room']->id
                    ) {
                        // Conflict detected: same room and time slot
                        $conflicts++;
                    }
                }
            }

            // Increment conflicts if availability checks failed
            if (!$classAvailabilities[$classId] || !$roomAvailabilities[$roomId] || !$facultyAvailabilities[$instructorId]) {
                $conflicts++;
            }

            // Keep track of scheduled time slots for each class
            $this->updateScheduledSlots($instructorScheduledSlots, $instructorId, $timeSlotId, $conflicts);
            $this->updateScheduledSlots($classScheduledSlots, $classId, $timeSlotId, $conflicts);
        }

        // Calculate fitness score based on conflicts (lower score is better)
        $fitness = 1 / ($conflicts + 1);

        // Additional capacity checks and penalties...

        return $fitness;
    }

    // Helper method to update scheduled slots and conflicts
    private function updateScheduledSlots(&$slots, $id, $timeSlotId, &$conflicts)
    {
        if (!isset($slots[$id])) {
            $slots[$id] = [];
        }
        if (in_array($timeSlotId, $slots[$id])) {
            // If the class is already scheduled in the same time slot, count as conflict
            $conflicts++;
        } else {
            $slots[$id][] = $timeSlotId;
        }
    }

    // Helper method to batch process class availability checks
    private function batchValidateClassRoomAvailability($data)
    {
        // Initialize array to store availability results
        $availabilities = [];

        foreach ($data as $item) {
            $validator = validator()->make($item, [
                'class_id' => new ClassRoomAvailabilityRule($item['time_slot_id']),
            ]);
            $availabilities[$item['class_id']] = !$validator->fails();
        }

        return $availabilities;
    }

    // Helper method to batch process room availability checks
    private function batchValidateRoomAvailability($data)
    {
        // Initialize array to store availability results
        $availabilities = [];

        foreach ($data as $item) {
            $validator = validator()->make($item, [
                'room_id' => new RoomAvailabilityRule($item['time_slot_id']),
            ]);
            $availabilities[$item['room_id']] = !$validator->fails();
        }

        return $availabilities;
    }

    // Helper method to batch process faculty availability checks
    private function batchValidateFacultyAvailability($data)
    {
        // Initialize array to store availability results
        $availabilities = [];

        foreach ($data as $item) {
            $validator = validator()->make($item, [
                'faculty_id' => new FacultyAvailabilityRule($item['time_slot_id']),
            ]);
            $availabilities[$item['faculty_id']] = !$validator->fails();
        }

        return $availabilities;
    }


    // Helper method to evolve the population
    private function evolvePopulation($population)
    {
        $newPopulation = [];

        // Elitism: Keep the best individual from the previous generation
        $bestIndividual = $this->getBestIndividual($population);
        $newPopulation[] = $bestIndividual;

        // Perform crossover and mutation on the remaining individuals
        for ($i = 1; $i < count($population); $i++) {
            // Selection: Select two parents from the current population
            $parent1 = $this->selection($population);
            $parent2 = $this->selection($population);

            // Crossover: Create offspring by combining genes of parents
            $offspring = $this->crossover($parent1, $parent2);

            // Mutation: Introduce random changes to offspring
            $offspring = $this->mutation($offspring);

            // Add offspring to the new population
            $newPopulation[] = $offspring;
        }

        return $newPopulation;
    }

    // Helper method to select an individual from the population
    private function selection($population)
    {
        // Roulette wheel selection: Select individuals based on their fitness
        $totalFitness = array_sum(array_map([$this, 'evaluateFitness'], $population));
        $randomValue = rand(0, $totalFitness);
        $cumulativeFitness = 0;

        foreach ($population as $individual) {
            $cumulativeFitness += $this->evaluateFitness($individual);
            if ($cumulativeFitness >= $randomValue) {
                return $individual;
            }
        }

        // In case of failure, return a random individual
        return $population[array_rand($population)];
    }

    // Helper method to perform crossover between two individuals
    private function crossover($parent1, $parent2)
    {
        // Single-point crossover: Choose a random crossover point
        $crossoverPoint = rand(0, count($parent1) - 1);

        // Create offspring by combining genes from parents
        $offspring = array_merge(array_slice($parent1, 0, $crossoverPoint), array_slice($parent2, $crossoverPoint));

        return $offspring;
    }

    // Helper method to perform mutation on an individual
    private function mutation($individual)
    {
        // Mutation rate: Adjust as needed
        $mutationRate = 0.1;

        // Perform mutation with a certain probability for each gene
        foreach ($individual as $key => $gene) {
            if (rand(0, 100) / 100 < $mutationRate) {
                // Mutate the gene (e.g., randomly change its value)
                // Implement mutation logic according to your requirements
                // For simplicity, let's randomly select a new value here
                $individual[$key] = $this->generateRandomGene();
            }
        }

        return $individual;
    }

    // Helper method to generate a random gene
    private function generateRandomGene()
    {
        // Implement logic to generate a random gene value
        // For simplicity, let's return a random value within a certain range
        return rand(1, 10);
    }

    // Helper method to get the best individual from the population
    private function getBestIndividual($population)
    {
        $bestFitness = -1;
        $bestIndividual = null;

        foreach ($population as $individual) {
            $fitness = $this->evaluateFitness($individual);
            if ($fitness > $bestFitness) {
                $bestFitness = $fitness;
                $bestIndividual = $individual;
            }
        }

        return $bestIndividual;
    }
}
