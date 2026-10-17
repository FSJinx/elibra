import { adminMenu } from "@/layouts/management/sidebar/admin.menu";
import { librarianMenu } from "@/layouts/management/sidebar/librarian.menu";

export const menus = {
  super_admin: adminMenu,
  admin: librarianMenu,
  librarian: librarianMenu,
}
