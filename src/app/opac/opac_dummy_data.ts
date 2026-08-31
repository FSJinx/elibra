export interface LibraryItem {
  id: string;
  title: string;
  itemType: 'book' | 'academic' | 'serial';
  category: string;
  authorOrCreator: string;
  callNumber: string;
  publicationYear: number;
  publisherOrInstitution: string;
  status: 'available' | 'borrowed' | 'reserved' | 'in_maintenance';
  location: string;
}

export const libraryData: LibraryItem[] = [
  // --- ACADEMIC ITEMS ---
  {
    id: "LIB-ACAD-001",
    title: "AI-Powered Microgrid Optimization for Rural Barangays",
    itemType: "academic",
    category: "capstone project",
    authorOrCreator: "Dela Cruz, Juan & Santos, Maria",
    callNumber: "CP 621.31 D37 2024",
    publicationYear: 2024,
    publisherOrInstitution: "College of Engineering and Architecture",
    status: "available",
    location: "Academic Archives - Shelf A3"
  },
  {
    id: "LIB-ACAD-002",
    title: "Market Expansion Strategy for Post-Pandemic Local Logistics Firms",
    itemType: "academic",
    category: "case study",
    authorOrCreator: "Reyes, Antonio M.",
    callNumber: "CS 658.8 R39 2023",
    publicationYear: 2023,
    publisherOrInstitution: "School of Business & Management",
    status: "borrowed",
    location: "Academic Archives - Shelf B1"
  },
  {
    id: "LIB-ACAD-003",
    title: "Socio-Economic Impacts of Ecotourism in Coastal Communities",
    itemType: "academic",
    category: "dissertation",
    authorOrCreator: "Dr. Alcantara, Sofia P.",
    callNumber: "DIS 338.47 AL15 2022",
    publicationYear: 2022,
    publisherOrInstitution: "Graduate School of Social Sciences",
    status: "available",
    location: "Graduate Research Room"
  },
  {
    id: "LIB-ACAD-004",
    title: "Feasibility of Solar-Powered Cold Storage Facilities in Northern Luzon",
    itemType: "academic",
    category: "feasibility study",
    authorOrCreator: "Garcia, Gabriel T.",
    callNumber: "FS 631.3 G16 2024",
    publicationYear: 2024,
    publisherOrInstitution: "College of Agriculture",
    status: "available",
    location: "Academic Archives - Shelf A5"
  },
  {
    id: "LIB-ACAD-005",
    title: "IT Support Integration and Network Security Infrastructure Report",
    itemType: "academic",
    category: "practicum report",
    authorOrCreator: "Villanueva, Paolo",
    callNumber: "PR 004.6 V71 2023",
    publicationYear: 2023,
    publisherOrInstitution: "College of Computer Studies",
    status: "available",
    location: "Academic Archives - Shelf C2"
  },
  {
    id: "LIB-ACAD-006",
    title: "Smart Waste Management System using IoT Sensors",
    itemType: "academic",
    category: "project study",
    authorOrCreator: "Mendoza, Clark & Ramos, Bea",
    callNumber: "PS 628.4 M52 2023",
    publicationYear: 2023,
    publisherOrInstitution: "Department of Information Technology",
    status: "borrowed",
    location: "Academic Archives - Shelf C4"
  },
  {
    id: "LIB-ACAD-007",
    title: "Microplastic Concentrations in Urban Waterways and Mitigating Bio-Filters",
    itemType: "academic",
    category: "thesis",
    authorOrCreator: "Torres, Katrina L.",
    callNumber: "TH 577.27 T63 2024",
    publicationYear: 2024,
    publisherOrInstitution: "College of Science",
    status: "reserved",
    location: "Graduate Research Room"
  },

  // --- BOOK ITEMS ---
  {
    id: "LIB-BOOK-001",
    title: "The Silent Patient",
    itemType: "book",
    category: "fiction",
    authorOrCreator: "Alex Michaelides",
    callNumber: "FIC Mic 2019",
    publicationYear: 2019,
    publisherOrInstitution: "Celadon Books",
    status: "borrowed",
    location: "General Fiction - Shelf 12"
  },
  {
    id: "LIB-BOOK-002",
    title: "Atomic Habits",
    itemType: "book",
    category: "non-fiction",
    authorOrCreator: "James Clear",
    callNumber: "158.1 Cle 2018",
    publicationYear: 2018,
    publisherOrInstitution: "Avery",
    status: "available",
    location: "Non-Fiction - Shelf 04"
  },
  {
    id: "LIB-BOOK-003",
    title: "Noli Me Tangere",
    itemType: "book",
    category: "filipiniana",
    authorOrCreator: "José Rizal",
    callNumber: "FIL 899.21 R52n 1996",
    publicationYear: 1996,
    publisherOrInstitution: "National Book Store",
    status: "available",
    location: "Filipiniana Section - Cabinet 01"
  },
  {
    id: "LIB-BOOK-004",
    title: "Data Structures and Algorithms in Java",
    itemType: "book",
    category: "textbook",
    authorOrCreator: "Robert Lafore",
    callNumber: "005.73 L13 2017",
    publicationYear: 2017,
    publisherOrInstitution: "Sams Publishing",
    status: "available",
    location: "Circulation Desk - Stack 08"
  },
  {
    id: "LIB-BOOK-005",
    title: "Merriam-Webster's Collegiate Dictionary (11th Edition)",
    itemType: "book",
    category: "dictionary",
    authorOrCreator: "Merriam-Webster Editorial Staff",
    callNumber: "REF 423 M57 2020",
    publicationYear: 2020,
    publisherOrInstitution: "Merriam-Webster, Inc.",
    status: "available",
    location: "Reference Section - Main Desk"
  },
  {
    id: "LIB-BOOK-006",
    title: "Encyclopædia Britannica 2024 World Atlas",
    itemType: "book",
    category: "atlas",
    authorOrCreator: "Britannica Editors",
    callNumber: "REF 912 B77 2024",
    publicationYear: 2024,
    publisherOrInstitution: "Encyclopædia Britannica, Inc.",
    status: "available",
    location: "Reference Section - Shelf R1"
  },
  {
    id: "LIB-BOOK-007",
    title: "Principles of Macroeconomics (Special Reserve)",
    itemType: "book",
    category: "reserved",
    authorOrCreator: "N. Gregory Mankiw",
    callNumber: "RES 339 M31 2021",
    publicationYear: 2021,
    publisherOrInstitution: "Cengage Learning",
    status: "reserved",
    location: "Reserve Desk (Room-Use Only)"
  },

  // --- SERIAL ITEMS ---
  {
    id: "LIB-SER-001",
    title: "IEEE Transactions on Software Engineering - Vol. 50 No. 2",
    itemType: "serial",
    category: "journal",
    authorOrCreator: "IEEE Computer Society",
    callNumber: "PER 005.1 I27 2024",
    publicationYear: 2024,
    publisherOrInstitution: "IEEE",
    status: "available",
    location: "Serials Section - Rack 05"
  },
  {
    id: "LIB-SER-002",
    title: "National Geographic - March 2024 Edition",
    itemType: "serial",
    category: "magazine",
    authorOrCreator: "National Geographic Society",
    callNumber: "PER 910 N21 2024-03",
    publicationYear: 2024,
    publisherOrInstitution: "National Geographic Partners",
    status: "available",
    location: "Serials Section - Magazine Display"
  },
  {
    id: "LIB-SER-003",
    title: "The Philippine Star (Daily Issue - August 28, 2024)",
    itemType: "serial",
    category: "newspaper",
    authorOrCreator: "PhilStar Daily Inc.",
    callNumber: "NEWS 070 P55 2024-08",
    publicationYear: 2024,
    publisherOrInstitution: "PhilStar Daily Inc.",
    status: "available",
    location: "Newspaper Archival Rack"
  },
  {
    id: "LIB-SER-004",
    title: "Bangko Sentral ng Pilipinas Annual Report 2023",
    itemType: "serial",
    category: "annual reports",
    authorOrCreator: "Bangko Sentral ng Pilipinas",
    callNumber: "AR 332.11 B22 2023",
    publicationYear: 2023,
    publisherOrInstitution: "BSP Communications Office",
    status: "available",
    location: "Government Documents Section"
  },
  {
    id: "LIB-SER-005",
    title: "Tech Pulse Quarterly Newsletter - Q2 2024",
    itemType: "serial",
    category: "newsletter",
    authorOrCreator: "Association for Computing Machinery Chapter",
    callNumber: "NL 004 T23 2024-Q2",
    publicationYear: 2024,
    publisherOrInstitution: "ACM Student Chapter",
    status: "available",
    location: "Serials Section - Rack 02"
  },
  {
    id: "LIB-SER-006",
    title: "Climate Change & Coastal Erosion in Southeast Asia (Clippings File)",
    itemType: "serial",
    category: "vertical",
    authorOrCreator: "Various Compiled Sources",
    callNumber: "VF 551.6 C61 2023",
    publicationYear: 2023,
    publisherOrInstitution: "Library Information Services",
    status: "available",
    location: "Vertical File Cabinet 03 - Folder 12"
  }
];